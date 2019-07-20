<?php

namespace FroshWebP\Services;

use Doctrine\Common\Collections\ArrayCollection;
use FroshWebP\Components\WebpEncoderInterface;

/**
 * Class WebpEncoderFactory
 * @package FroshWebP\Services
 */
class WebpEncoderFactory
{
    /**
     * @var WebpEncoderInterface[]
     */
    private $encoders;

    /**
     * WebpEncoderFactory constructor.
     * @param ArrayCollection $encoders
     */
    public function __construct(ArrayCollection $encoders)
    {
        $this->encoders = $encoders->toArray();
    }

    /**
     * @return WebpEncoderInterface[]
     */
    public function getEncoders()
    {
        return $this->encoders;
    }

    /**
     * @param WebpEncoderInterface[] $encoders
     *
     * @return WebpEncoderInterface[]
     */
    public static function onlyRunnable(array $encoders)
    {
        return array_filter(
            $encoders,
            function (WebpEncoderInterface $encoder) {
                return $encoder->isRunnable();
            }
        );
    }
}
