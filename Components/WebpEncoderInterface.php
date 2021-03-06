<?php

namespace FroshWebP\Components;

/**
 * Interface WebpEncoderInterface
 */
interface WebpEncoderInterface
{
    /** @return string */
    public function getName();

    /**
     * @param resource $image
     * @param int      $quality
     *
     * @return string
     */
    public function encode($image, $quality);

    /** @return bool */
    public function isRunnable();
}
