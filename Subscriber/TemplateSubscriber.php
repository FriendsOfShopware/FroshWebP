<?php


namespace ShyimWebP\Subscriber;


use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;

class TemplateSubscriber implements SubscriberInterface
{
    /**
     * @return array
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend' => 'addTemplateDir',
            'Enlight_Controller_Action_PostDispatch_Widgets' => 'addTemplateDir',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     * @return void
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public function addTemplateDir(\Enlight_Event_EventArgs $args)
    {
        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');

        $controller->View()->addTemplateDir(dirname(__DIR__) .  '/Resources/views');
    }
}