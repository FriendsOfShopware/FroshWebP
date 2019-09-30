<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use FroshWebP\Services\WebpEncoderFactory;
use Shopware_Controllers_Backend_Systeminfo;

/**
 * Class SysteminfoSubscriber
 */
class SysteminfoSubscriber implements SubscriberInterface
{
    /**
     * @var WebpEncoderFactory
     */
    private $webpEncoderFactory;

    /**
     * SysteminfoSubscriber constructor.
     *
     * @param WebpEncoderFactory $webpEncoderFactory
     */
    public function __construct(WebpEncoderFactory $webpEncoderFactory)
    {
        $this->webpEncoderFactory = $webpEncoderFactory;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Systeminfo' => 'onPostDispatchSystemInfo',
            'Enlight_Controller_Action_Backend_Systeminfo_webpencoders' => 'onWebpEncoders',
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     *
     * @return bool
     */
    public function onWebpEncoders(Enlight_Event_EventArgs $args): bool
    {
        /** @var Shopware_Controllers_Backend_Systeminfo $subject */
        $subject = $args->getSubject();

        $results = [];

        foreach ($this->webpEncoderFactory->getEncoders() as $encoder) {
            $results[] = [
                'name' => $encoder->getName(),
                'available' => $encoder->isRunnable(),
            ];
        }

        $subject->View()->assign('success', true);
        $subject->View()->assign('data', array_values($results));
        $subject->View()->assign('total', count($results));

        return true;
    }

    /**
     * @param Enlight_Event_EventArgs $args
     */
    public function onPostDispatchSystemInfo(Enlight_Event_EventArgs $args): void
    {
        /** @var Shopware_Controllers_Backend_Systeminfo $subject */
        $subject = $args->getSubject();

        if ($subject->Request()->getActionName() !== 'load') {
            return;
        }

        $subject->View()->addTemplateDir(dirname(__DIR__) . '/Resources/views');
        $subject->View()->extendsTemplate('backend/frosh_webp/systeminfo/webp_encoder.js');
        $subject->View()->extendsTemplate('backend/frosh_webp/systeminfo/window.js');
    }
}
