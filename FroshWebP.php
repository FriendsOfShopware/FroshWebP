<?php

namespace FroshWebP;

use FroshWebP\Services\WebpEncoders\PhpGd;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

/**
 * Class FroshWebP
 * @package FroshWebP
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
}
