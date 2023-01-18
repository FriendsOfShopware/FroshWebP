<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Action;
use Enlight_Event_EventArgs;

/**
 * Class TemplateSubscriber
 */
class TemplateSubscriber implements SubscriberInterface
{
    /**
     * @var array
     */
    private $pluginConfig;

    /**
     * TemplateSubscriber constructor.
     *
     * @param array $pluginConfig
     */
    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    /**
     * @return array
     *
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Widgets' => 'addTemplateDir',
            'Enlight_Controller_Action_PreDispatch_Frontend' => 'addTemplateDir',
            'Theme_Compiler_Collect_Javascript_Files_FilterResult' => 'onFilterJavascriptFiles',
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     *
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public function addTemplateDir(Enlight_Event_EventArgs $args): void
    {
        /** @var Enlight_Controller_Action $controller */
        $controller = $args->get('subject');

        if (empty($this->pluginConfig['enableWebPInFrontend'])) {
            return;
        }

        $controller->View()->addTemplateDir(dirname(__DIR__) . '/Resources/views');
    }

    /**
     * @return list<string>
     */
    public function onFilterJavascriptFiles(Enlight_Event_EventArgs $args): ?array
    {
        if (!empty($this->pluginConfig['enableWebPJavascriptInFrontend'])) {
            return null;
        }

        return array_filter(
            $args->getReturn(),
            function(string $item) {
                return stripos($item, 'FroshWebP/Resources/frontend/js') === false;
            }
        );
    }
}
