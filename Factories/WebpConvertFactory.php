<?php

namespace FroshWebP\Factories;

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
     * @throws \Exception
     *
     * @return mixed
     */
    public static function build($imgContent, $runnableEncoder, $webpQuality)
    {
        if ($imgContent === false) {
            throw new \Exception('Could not load image');
        }
        imagepalettetotruecolor($imgContent);

        return current($runnableEncoder)->encode($imgContent, $webpQuality);
    }
}
