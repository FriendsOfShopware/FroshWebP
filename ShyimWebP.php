<?php


namespace ShyimWebP;


use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;

class ShyimWebP extends Plugin
{
    public function install(InstallContext $context)
    {
        if (!function_exists('imagewebp')) {
            throw new \RuntimeException('GD is installed without webp support');
        }
    }
}