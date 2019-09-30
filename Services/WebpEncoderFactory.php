<?php

namespace FroshWebP\Services;

use FroshWebP\Components\WebpEncoderInterface;
use Traversable;

/**
 * Class WebpEncoderFactory
 */
class WebpEncoderFactory
{
    /**
     * @var WebpEncoderInterface[]
     */
    private $encoders;

    /**
     * WebpEncoderFactory constructor.
     *
     * @param Traversable $encoders
     */
    public function __construct(Traversable $encoders)
    {
        $this->encoders = iterator_to_array($encoders);
    }

    /**
     * @return WebpEncoderInterface[]
     */
    public function getEncoders(): array
    {
        return $this->encoders;
    }

    /**
     * @param WebpEncoderInterface[] $encoders
     *
     * @return WebpEncoderInterface[]
     */
    public static function onlyRunnable(array $encoders): array
    {
        return array_filter(
            $encoders,
            static function (WebpEncoderInterface $encoder) {
                return $encoder->isRunnable();
            }
        );
    }
}
