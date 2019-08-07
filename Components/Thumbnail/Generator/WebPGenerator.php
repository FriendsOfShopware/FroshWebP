<?php

namespace FroshWebP\Components\Thumbnail\Generator;

use Exception;
use FroshWebP\Components\WebpEncoderInterface;
use FroshWebP\Services\WebpEncoderFactory;
use RuntimeException;
use Shopware\Bundle\MediaBundle\Exception\OptimizerNotFoundException;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Bundle\MediaBundle\OptimizerServiceInterface;
use Shopware\Components\Thumbnail\Generator\GeneratorInterface;
use Shopware_Components_Config;

/**
 * Class WebPGenerator
 * @package FroshWebP\Components\Thumbnail\Generator
 */
class WebPGenerator implements GeneratorInterface
{
    /**
     * @var bool
     */
    private $fixGdImageBlur;

    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    /**
     * @var OptimizerServiceInterface
     */
    private $optimizerService;

    /** @var WebpEncoderFactory */
    private $webpEncoderFactory;

    /** @var WebpEncoderInterface[] */
    private $webpEncoders;

    /**
     * @param Shopware_Components_Config $config
     * @param MediaServiceInterface $mediaService
     * @param OptimizerServiceInterface $optimizerService
     * @param WebpEncoderFactory $encoderFactory
     */
    public function __construct(
        Shopware_Components_Config $config,
        MediaServiceInterface $mediaService,
        OptimizerServiceInterface $optimizerService,
        WebpEncoderFactory $encoderFactory
    )
    {
        $this->fixGdImageBlur = $config->get('thumbnailNoiseFilter');
        $this->mediaService = $mediaService;
        $this->optimizerService = $optimizerService;
        $this->webpEncoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function createThumbnail($imagePath, $destination, $maxWidth, $maxHeight, $keepProportions = false, $quality = 90)
    {
        if (!$this->mediaService->has($imagePath)) {
            throw new Exception('File not found: ' . $imagePath);
        }

        $content = $this->mediaService->read($imagePath);
        $image = $this->createImageResource($content, $imagePath);

        // Determines the width and height of the original image
        $originalSize = $this->getOriginalImageSize($image);

        if (empty($maxHeight)) {
            $maxHeight = $maxWidth;
        }

        $newSize = [
            'width' => $maxWidth,
            'height' => $maxHeight,
        ];

        if ($keepProportions) {
            $newSize = $this->calculateProportionalThumbnailSize($originalSize, $maxWidth, $maxHeight);
        }

        $fileExt = $this->getImageExtension($destination);

        $newImage = $this->createNewImage($image, $originalSize, $newSize, $fileExt);

        if ($this->fixGdImageBlur) {
            $this->fixGdImageBlur($newSize, $newImage);
        }

        $this->saveImage($destination, $newImage, $quality);
        $this->optimizeImage($destination);

        if ($this->webpEncoders === null) {
            $this->webpEncoders = WebpEncoderFactory::onlyRunnable($this->webpEncoderFactory->getEncoders());
        }

        if (!empty($this->webpEncoders)) {
            $webpPath = str_replace($fileExt, 'webp', $destination);
            $this->mediaService->write($webpPath, current($this->webpEncoders)->encode($newImage, $quality));
            $this->optimizeImage($webpPath);
        }

        // Removes both the original and the new created image from memory
        imagedestroy($newImage);
        imagedestroy($image);
    }

    /**
     * Returns an array with a width and height index
     * according to the passed sizes
     *
     * @param resource $imageResource
     *
     * @return array
     */
    private function getOriginalImageSize($imageResource): array
    {
        return [
            'width' => imagesx($imageResource),
            'height' => imagesy($imageResource),
        ];
    }

    /**
     * Determines the extension of the file according to
     * the given path and calls the right creation
     * method for the image extension
     *
     * @param string $fileContent
     *
     * @param $imagePath
     * @return resource
     */
    private function createImageResource($fileContent, $imagePath)
    {
        if (!$image = @imagecreatefromstring($fileContent)) {
            throw new RuntimeException(sprintf('Image is not in a recognized format (%s)', $imagePath));
        }

        return $image;
    }

    /**
     * Returns the extension of the file with passed path
     *
     * @param string
     *
     * @return string
     */
    private function getImageExtension($path): string
    {
        $pathInfo = pathinfo($path);

        return $pathInfo['extension'];
    }

    /**
     * Calculate image proportion and set the new resolution
     *
     * @param array $originalSize
     * @param int $width
     * @param int $height
     *
     * @return array
     */
    private function calculateProportionalThumbnailSize(array $originalSize, $width, $height): array
    {
        // Source image size
        $srcWidth = $originalSize['width'];
        $srcHeight = $originalSize['height'];

        // Calculate the scale factor
        if ($width === 0) {
            $factor = $height / $srcHeight;
        } elseif ($height === 0) {
            $factor = $width / $srcWidth;
        } else {
            $factor = min($width / $srcWidth, $height / $srcHeight);
        }

        if ($factor >= 1) {
            $dstWidth = $srcWidth;
            $dstHeight = $srcHeight;
            $factor = 1;
        } else {
            //Get the destination size
            $dstWidth = round($srcWidth * $factor);
            $dstHeight = round($srcHeight * $factor);
        }

        return [
            'width' => $dstWidth,
            'height' => $dstHeight,
            'proportion' => $factor,
        ];
    }

    /**
     * @param resource $image
     * @param array $originalSize
     * @param array $newSize
     * @param bool $extension
     *
     * @return resource
     */
    private function createNewImage($image, $originalSize, $newSize, $extension)
    {
        // Creates a new image with given size
        $newImage = imagecreatetruecolor($newSize['width'], $newSize['height']);

        if (in_array($extension, ['jpg', 'jpeg'])) {
            $background = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $background);
        } else {
            // Disables blending
            imagealphablending($newImage, false);
        }
        // Saves the alpha informations
        imagesavealpha($newImage, true);
        // Copies the original image into the new created image with resampling
        imagecopyresampled(
            $newImage,
            $image,
            0,
            0,
            0,
            0,
            $newSize['width'],
            $newSize['height'],
            $originalSize['width'],
            $originalSize['height']
        );

        return $newImage;
    }

    /**
     * Fix #fefefe in white backgrounds
     *
     * @param array $newSize
     * @param resource $newImage
     */
    private function fixGdImageBlur($newSize, $newImage): void
    {
        $colorWhite = imagecolorallocate($newImage, 255, 255, 255);
        $processHeight = $newSize['height'] + 0;
        $processWidth = $newSize['width'] + 0;
        for ($y = 0; $y < $processHeight; ++$y) {
            for ($x = 0; $x < $processWidth; ++$x) {
                $colorat = imagecolorat($newImage, $x, $y);
                $r = ($colorat >> 16) & 0xFF;
                $g = ($colorat >> 8) & 0xFF;
                $b = $colorat & 0xFF;
                if (($r === 253 && $g === 253 && $b === 253) || ($r === 254 && $g === 254 && $b === 254)) {
                    imagesetpixel($newImage, $x, $y, $colorWhite);
                }
            }
        }
    }

    /**
     * @param string $destination
     * @param resource $newImage
     * @param int $quality - JPEG quality
     */
    private function saveImage($destination, $newImage, $quality): void
    {
        ob_start();
        // saves the image information into a specific file extension
        switch (strtolower($this->getImageExtension($destination))) {
            case 'png':
                imagepng($newImage);
                break;
            case 'gif':
                imagegif($newImage);
                break;
            default:
                imagejpeg($newImage, null, $quality);
                break;
        }

        $content = ob_get_clean();

        $this->mediaService->write($destination, $content);
    }

    /**
     * @param string $destination
     */
    private function optimizeImage($destination): void
    {
        $tmpFilename = $this->downloadImage($destination);

        try {
            $this->optimizerService->optimize($tmpFilename);
            $this->uploadImage($destination, $tmpFilename);
        } catch (OptimizerNotFoundException $exception) {
            // empty catch intended since no optimizer is available
        }
        unlink($tmpFilename);
    }

    /**
     * @param string $destination
     *
     * @return string
     */
    private function downloadImage($destination): string
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), 'optimize_image');
        $handle = fopen($tmpFilename, 'wb');

        stream_copy_to_stream(
            $this->mediaService->readStream($destination),
            $handle
        );

        return $tmpFilename;
    }

    /**
     * @param string $destination
     * @param string $tmpFilename
     */
    private function uploadImage($destination, $tmpFilename): void
    {
        $fileHandle = fopen($tmpFilename, 'rb');
        $this->mediaService->writeStream($destination, $fileHandle);
        fclose($fileHandle);
    }
}
