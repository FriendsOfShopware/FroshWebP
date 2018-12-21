<?php

namespace FroshWebP;

use Shopware\Components\DependencyInjection\Compiler\TagReplaceTrait;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FroshWebP extends Plugin
{
    use TagReplaceTrait;

    /** {@inheritdoc} */
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
}
