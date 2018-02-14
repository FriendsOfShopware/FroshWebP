<?php

namespace ShyimWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Models\Media\Media;

/**
 * Class MediaUploadSubscriber
 * @package ShyimWebP\Subscriber
 */
class MediaUploadSubscriber implements SubscriberInterface
{
    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Shopware\Models\Media\Media::postPersist' => 'onFileUploaded'
        ];
    }

    /**
     * MediaUploadSubscriber constructor.
     * @param MediaServiceInterface $mediaService
     */
    public function __construct(MediaServiceInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    /**
     * @param Enlight_Event_EventArgs  $args
     */
    public function onFileUploaded(Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware\Models\Media\Media $media */
        $media = $args->get('entity');

        $webpPath = str_replace($media->getExtension(), 'webp', $media->getPath());
        $im = imagecreatefromstring($this->mediaService->read($media->getPath()));

        ob_start();

        imagewebp($im, null, 80);

        $content = ob_get_contents();
        ob_end_clean();

        $this->mediaService->write($webpPath, $content);
    }
}