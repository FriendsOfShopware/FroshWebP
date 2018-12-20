<?php


namespace FroshWebP\Subscriber;


use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;

/**
 * Class TemplateSubscriber
 * @package FroshWebP\Subscriber
 */
class TemplateSubscriber implements SubscriberInterface
{
    /**
     * @var array
     */
    private $pluginConfig;

    /**
     * @return array
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Widgets' => 'addTemplateDir',
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'addTemplateDir'
        ];
    }

    /**
     * TemplateSubscriber constructor.
     * @param array $pluginConfig
     */
    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
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

        if (empty($this->pluginConfig['enableWebPInFrontend'])) {
            return;
        }

        $controller->View()->addTemplateDir(dirname(__DIR__) .  '/Resources/views');
    }
}