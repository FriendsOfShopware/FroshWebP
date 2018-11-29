<?php

namespace ShyimWebP\Services;

use IteratorAggregate;
use ShyimWebP\Components\WebpEncoderInterface;

class WebpEncoderFactory
{
    /** @var WebpEncoderInterface[] */
    private $encoders;

    public function __construct(IteratorAggregate $encoders)
    {
        $this->encoders = iterator_to_array($encoders);
    }

    /** @return WebpEncoderInterface[] */
    public function getEncoders()
    {
        return $this->encoders;
    }

    /**
     * @param WebpEncoderInterface[] $encoders
     * @return WebpEncoderInterface[]
     */
    public static function onlyRunnable(array $encoders)
    {
        return array_values(array_filter(
            $encoders,
            function (WebpEncoderInterface $encoder) {
                return $encoder->isRunnable();
            }
        ));
    }
}
