<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use FroshWebP\Services\WebpEncoderFactory;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;

/**
 * Class MediaUploadSubscriber
 */
class MediaUploadSubscriber implements SubscriberInterface
{
    const CAN_HANDLE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
    ];

    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    /**
     * @var WebpEncoderFactory
     */
    private $encoderFactory;
    /**
     * @var array
     */
    private $config;

    /**
     * MediaUploadSubscriber constructor.
     *
     * @param MediaServiceInterface $mediaService
     * @param WebpEncoderFactory    $encoderFactory
     * @param array                 $config
     */
    public function __construct(MediaServiceInterface $mediaService, WebpEncoderFactory $encoderFactory, array $config)
    {
        $this->mediaService = $mediaService;
        $this->encoderFactory = $encoderFactory;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware\Models\Media\Media::postPersist' => 'onFileUploaded',
            'Shopware\Models\Media\Media::postRemove' => 'onFileRemoved',
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onFileRemoved(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware\Models\Media\Media $media */
        $media = $args->get('entity');

        $extension = strtolower($media->getExtension());

        if (!in_array($extension, self::CAN_HANDLE_EXTENSIONS)) {
            return;
        }

        $webpPath = str_replace($media->getExtension(), 'webp', $media->getPath());

        if ($this->mediaService->has($webpPath)) {
            $webpMedia = clone $media;
            $webpMedia->setPath($webpPath);
            $webpMedia->setExtension('webp');
            $webpMedia->removeThumbnails();

            $this->mediaService->delete($webpPath);
        }
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onFileUploaded(Enlight_Event_EventArgs $args)
    {
        $runnableEncoders = WebpEncoderFactory::onlyRunnable($this->encoderFactory->getEncoders());

        if (($encoder = current($runnableEncoders)) !== false) {
            /** @var \Shopware\Models\Media\Media $media */
            $media = $args->get('entity');
            $extension = strtolower($media->getExtension());

            if (!in_array($extension, self::CAN_HANDLE_EXTENSIONS)) {
                return;
            }

            $webpPath = str_replace($media->getExtension(), 'webp', $media->getPath());
            $im = imagecreatefromstring($this->mediaService->read($media->getPath()));
            imagepalettetotruecolor($im);
            $content = $encoder->encode($im, $this->config['webPQuality']);
            imagedestroy($im);

            $this->mediaService->write($webpPath, $content);
        }
    }
}
