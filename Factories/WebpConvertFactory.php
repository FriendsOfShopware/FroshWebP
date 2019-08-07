<?php

namespace FroshWebP\Factories;

use Exception;

/**
 * Class WebpConvertFactory
 */
class WebpConvertFactory
{
    /**
     * @param $imgContent
     * @param $runnableEncoder
     * @param $webpQuality
     *
     * @return mixed
     * @throws Exception
     *
     */
    public static function build($imgContent, $runnableEncoder, $webpQuality)
    {
        if ($imgContent === false) {
            throw new Exception('Could not load image');
        }
        imagepalettetotruecolor($imgContent);

        return current($runnableEncoder)->encode($imgContent, $webpQuality);
    }
}
