<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;

/**
 * Class ModernizrSubscriber
 * @package FroshWebP\Subscriber
 */
class ModernizrSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Compiler_Collect_Javascript_Files_FilterResult' => 'filterJavascriptFiles',
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     * @return mixed
     */
    public function filterJavascriptFiles(Enlight_Event_EventArgs $args)
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
