<?php

namespace FroshWebP\Components;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\MediaBundle\MediaService;
use Shopware\Components\Theme\Inheritance as InheritanceCore;
use Shopware\Models\Shop;

class Inheritance extends InheritanceCore
{
    /**
     * @var Inheritance
     */
    private $inheritance;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var MediaService
     */
    private $mediaService;

    public function __construct(InheritanceCore $inheritance, Connection $connection, MediaService $mediaService)
    {
        $this->inheritance = $inheritance;
        $this->connection = $connection;
        $this->mediaService = $mediaService;
    }

    public function buildInheritances(Shop\Template $template)
    {
        return $this->inheritance->buildInheritances($template);
    }

    public function getInheritancePath(Shop\Template $template)
    {
        return $this->inheritance->getInheritancePath($template);
    }

    public function buildConfig(Shop\Template $template, Shop\Shop $shop, $lessCompatible = true)
    {
        $config = $this->inheritance->buildConfig($template, $shop, $lessCompatible);

        if (!$lessCompatible) {
            $config = array_merge($config, $this->getWebpLogos($template, $shop));
        }

        return $config;
    }

    public function getTemplateDirectories(Shop\Template $template)
    {
        return $this->inheritance->getTemplateDirectories($template);
    }

    public function getSmartyDirectories(Shop\Template $template)
    {
        return $this->inheritance->getSmartyDirectories($template);
    }

    public function getTemplateCssFiles(Shop\Template $template)
    {
        return $this->inheritance->getTemplateCssFiles($template);
    }

    public function getTemplateJavascriptFiles(Shop\Template $template)
    {
        return $this->inheritance->getTemplateJavascriptFiles($template);
    }

    public function getTheme(Shop\Template $template)
    {
        return $this->inheritance->getTheme($template);
    }

    private function getWebpLogos(Shop\Template $template, Shop\Shop $shop)
    {
        $logos = $this->connection->createQueryBuilder()
            ->addSelect('element.name')
            ->addSelect('eValue.value')
            ->from('s_core_templates_config_elements', 'element')
            ->innerJoin('element', 's_core_templates_config_values', 'eValue', 'eValue.element_id = element.id')
            ->andWhere('name IN (:names)')
            ->andWhere('eValue.shop_id = :shopId')
            ->andWhere('element.template_id = :templateId')
            ->setParameter('names', [
                'mobileLogo',
                'tabletLogo',
                'tabletLandscapeLogo',
                'desktopLogo',
            ], Connection::PARAM_STR_ARRAY)
            ->setParameter('templateId', $template->getId())
            ->setParameter('shopId', $shop->getMain() ? $shop->getMain()->getId() : $shop->getId())
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);

        $result = [];
        foreach ($logos as $key => $value) {
            $value = unserialize($value);
            $ext = pathinfo($value, PATHINFO_EXTENSION);
            $value = str_replace('.' . $ext, '.webp', $value);

            $result[$key . 'Webp'] = $this->mediaService->getUrl($value);
        }

        return $result;
    }
}