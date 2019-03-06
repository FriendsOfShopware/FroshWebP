<?php


namespace FroshWebP\Subscriber;


use Enlight\Event\SubscriberInterface;

class ModernizrSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Compiler_Collect_Javascript_Files_FilterResult' => 'filterJavascriptFiles'
        ];
    }

    public function filterJavascriptFiles(\Enlight_Event_EventArgs $args)
    {
        $files = $args->getReturn();

        foreach ($files as &$file) {
            if (strpos($file, 'modernizr.custom.35977.js') !== false) {
                $file = dirname(__DIR__) . '/Resources/frontend/modernizr-custom.js';
            }
        }
        unset($file);

        return $files;
    }
}