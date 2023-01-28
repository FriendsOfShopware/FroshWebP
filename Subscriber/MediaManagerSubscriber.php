<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;

class MediaManagerSubscriber implements SubscriberInterface
{
    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    public function __construct(MediaServiceInterface $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_MediaManager' => 'onPostDispatchMediaManager'
        ];
    }

    public function onPostDispatchMediaManager(\Enlight_Controller_ActionEventArgs $args): void
    {
        $controller = $args->getSubject();
        $view = $controller->View();
        $request = $controller->Request();
        $response = $controller->Response();

        if ($request->getActionName() === 'getAlbumMedia') {
            $data = $view->getAssign('data');

            foreach ($data as &$media) {
                foreach ($media['thumbnails'] as $size => $thumbnail) {
                    $media['thumbnails'][$size . '_webp'] = $this->mediaService->getUrl(dirname($media['virtualPath']) . '/thumbnail/' . pathinfo($media['virtualPath'], PATHINFO_FILENAME) . '_' . $size . '.webp');
                }

                $media['webpPath'] = $this->mediaService->getUrl(str_replace($media['extension'], 'webp', $media['virtualPath']));
            }

            $view->assign('data', $data);
        }
    }
}
