<?php

namespace FroshWebP;

use FroshWebP\Services\WebpEncoders\PhpGd;
use Shopware\Components\DependencyInjection\Compiler\TagReplaceTrait;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FroshWebP
 * @package FroshWebP
 */
class FroshWebP extends Plugin
{
    use TagReplaceTrait;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $this->replaceArgumentWithTaggedServices(
            $builder,
            'frosh_webp.collections.webp_encoders',
            'frosh_webp.webp_encoder',
            0
        );
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
}
