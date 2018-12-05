<?php

namespace ShyimWebP\Services\WebpEncoders;

use ShyimWebP\Components\WebpEncoderInterface;

class PhpGd implements WebpEncoderInterface
{
    /** {@inheritdoc} */
    public function getName()
    {
        return 'PHP GD';
    }

    /** {@inheritdoc} */
    public function encode($image, $quality)
    {
        ob_start();
        imagewebp($image, null, $quality);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /** {@inheritdoc} */
    public function isRunnable()
    {
        return function_exists('imagewebp')
            && defined('IMG_WEBP')
            && (imagetypes() & IMG_WEBP) === IMG_WEBP;
    }
}
