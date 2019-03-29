<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use FroshWebP\Services\WebpEncoderFactory;

class SysteminfoSubscriber implements SubscriberInterface
{
    /**
     * @var WebpEncoderFactory
     */
    private $webpEncoderFactory;

    public function __construct(WebpEncoderFactory $webpEncoderFactory)
    {
        $this->webpEncoderFactory = $webpEncoderFactory;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Systeminfo' => 'onPostDispatchSystemInfo',
            'Enlight_Controller_Action_Backend_Systeminfo_webpencoders' => 'onWebpEncoders',
        ];
    }

    public function onWebpEncoders(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Systeminfo $subject */
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

    public function onPostDispatchSystemInfo(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_Systeminfo $subject */
        $subject = $args->getSubject();

        if ($subject->Request()->getActionName() !== 'load') {
            return;
        }

        $subject->View()->addTemplateDir(dirname(__DIR__) . '/Resources/views');
        $subject->View()->extendsTemplate('backend/frosh_webp/systeminfo/webp_encoder.js');
        $subject->View()->extendsTemplate('backend/frosh_webp/systeminfo/window.js');
    }
}
