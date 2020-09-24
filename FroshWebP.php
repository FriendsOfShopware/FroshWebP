<?php

namespace FroshWebP;

use FroshWebP\Services\WebpEncoders\PhpGd;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Models\Config\Element;

/**
 * Class FroshWebP
 */
class FroshWebP extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Controllers_Backend_Config_Before_Save_Config_Element' => 'onConfigSave'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function install(InstallContext $context)
    {
        $gd = new PhpGd();

        if (!$gd->isRunnable()) {
            $context->scheduleMessage('PHP is not compiled with WebP Support. Please contact your Hosting provider!');
        }
    }

    public function activate(ActivateContext $context)
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_ALL);
    }

    public function deactivate(DeactivateContext $context)
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_ALL);
    }

    public function onConfigSave(\Enlight_Event_EventArgs $args)
    {
        /** @var Element $element */
        $element = $args->get('element');

        if ($element->getName() !== 'enableWebPInFrontend') {
            return;
        }

        $this->container->get('shopware.cache_manager')->clearTemplateCache();
    }
}
