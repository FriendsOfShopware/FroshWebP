<?php

namespace FroshWebP\Services\WebpEncoders;

use FroshWebP\Components\WebpEncoderInterface;

class PhpGd implements WebpEncoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'PHP GD';
    }

    /**
     * {@inheritdoc}
     */
    public function encode($image, $quality)
    {
        ob_start();
        imagewebp($image, null, $quality);
        if (ob_get_length() % 2 === 1) {
            echo "\0";
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function isRunnable()
    {
        return function_exists('imagewebp')
            && defined('IMG_WEBP')
            && (imagetypes() & IMG_WEBP) === IMG_WEBP;
    }
}
