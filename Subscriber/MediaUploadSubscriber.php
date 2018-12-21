<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use FroshWebP\Services\WebpEncoderFactory;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Models\Media\Media;

/**
 * Class MediaUploadSubscriber
 */
class MediaUploadSubscriber implements SubscriberInterface
{
    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    /**
     * @var WebpEncoderFactory
     */
    private $encoderFactory;

    /**
     * MediaUploadSubscriber constructor.
     *
     * @param MediaServiceInterface $mediaService
     * @param WebpEncoderFactory    $encoderFactory
     */
    public function __construct(MediaServiceInterface $mediaService, WebpEncoderFactory $encoderFactory)
    {
        $this->mediaService = $mediaService;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware\Models\Media\Media::postPersist' => 'onFileUploaded',
        ];
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

            $webpPath = str_replace($media->getExtension(), 'webp', $media->getPath());
            $im = imagecreatefromstring($this->mediaService->read($media->getPath()));
            imagepalettetotruecolor($im);
            $content = $encoder->encode($im, 80);
            imagedestroy($im);

            $this->mediaService->write($webpPath, $content);
        }
    }
}
