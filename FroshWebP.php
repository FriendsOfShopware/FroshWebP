<?php

namespace FroshWebP;

use FroshWebP\Services\WebpEncoders\PhpGd;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;

/**
 * Class FroshWebP
 */
class FroshWebP extends Plugin
{
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
}
