<?php

namespace ShyimWebP;

use Shopware\Components\DependencyInjection\Compiler\TagReplaceTrait;
use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ShyimWebP extends Plugin
{
    use TagReplaceTrait;

    /** {@inheritdoc} */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $this->replaceArgumentWithTaggedService(
            $builder,
            'shyim_webp.collections.webp_encoders',
            'shyim_webp.webp_encoder',
            0
        );
    }
}
