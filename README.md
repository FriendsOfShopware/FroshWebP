# WebP Support for Shopware

[![Join the chat at https://gitter.im/FriendsOfShopware/Lobby](https://badges.gitter.im/FriendsOfShopware/Lobby.svg)](https://gitter.im/FriendsOfShopware/Lobby)

[WebP Browser-Support](http://caniuse.com/#search=webp)

This plugin generates additional webp Thumbnails and uses is in various locations like detail, listing, emotion as preferred type using html5 picture tag. Browsers which does not support webp, will get normally jpg. 
**After installation of the Plugin regenerating all thumbnails is required.**

# Requirements

* Shopware Version 5.3+
* any webp encoder
  * php-gd with webp support
  * cwebp executable in PATH
  
# Installation

## Zip Installation package for the Shopware Plugin Manager

* Download the [latest plugin version](https://github.com/FriendsOfShopware/FroshWebP/releases/latest/) (e.g. `FroshWebP-1.0.0.zip`)
* Upload and install plugin using Plugin Manager

## Git Version
* Checkout Plugin in `/custom/plugins/FroshWebP`
* Change to Directory and run `composer install` to install the dependencies
* Install the Plugin with the Plugin Manager

## Install with composer
* Change to your root Installation of shopware
* Run command `composer require frosh/webp` and install and active plugin with Plugin Manager 

## After Installation
* Download google binaries if neccessary `php bin/console frosh:webp:download-google-binaries`
* Generate all Thumbnails new with ``php bin/console sw:thumbnail:generate -f``


## Compatibility to LazyLoading Plugin available on Community-Store
With small changes this Plugin is compatible with LazyLoader https://store.shopware.com/ies6685271780164/lazy-loading.html

* Make change to custom/plugins/IesLazyLoading/Resources/views/frontend/listingproduct-box/box-big-image.tpl 
* Make change to custom/plugins/IesLazyLoading/Resources/views/frontend/listingproduct-box/product-image.tpl
* Take Care about UPDATES of LazyLoader! I will inform the Plugin developer, may be it will be added to the Plugin. - If so You'll read it here!

box-big-image.tpl:
```
{extends file="parent:frontend/listing/product-box/box-big-image.tpl"}

{block name="frontend_listing_box_article_image_picture_element"}	
	<picture>
        {if isset($sArticle.image.thumbnails[1].webp)}
            <source data-srcset="{$sArticle.image.thumbnails[1].webp.sourceSet}" type="image/webp">
        {/if}
        <img class="lazy"
        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
        {if $iesLazyLoadingEffect}data-effect="{$iesLazyLoadingEffect}"{/if}
        {if $iesLazyLoadingEffectTime}data-effectTime="{$iesLazyLoadingEffectTime}"{/if}
        data-src="{$sArticle.image.thumbnails[1].source}"
        data-srcset="{$sArticle.image.thumbnails[1].sourceSet}"
        alt="{$desc}"
        title="{$desc|truncate:160}"
    	/>
    </picture>
    {block name='frontend_listing_box_article_image_picture_element_no_script'}
        <noscript>{$smarty.block.parent}</noscript>
    {/block}
{/block}
```

product-image.tpl:
```
{extends file="parent:frontend/listing/product-box/product-image.tpl"}

{block name='frontend_listing_box_article_image_picture_element'}
    {$imageSize = $iesLazyLoadingProductImageSize}
    {if !$imageSize}
        {$imageSize = 0}
    {/if}
	<picture>
        {if isset($sArticle.image.thumbnails[0].webp)}
            <source data-srcset="{$sArticle.image.thumbnails[0].webp.sourceSet}" type="image/webp">
        {/if}
        <img class="lazy"
        src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
        {if $iesLazyLoadingEffect}data-effect="{$iesLazyLoadingEffect}"{/if}
        {if $iesLazyLoadingEffectTime}data-effectTime="{$iesLazyLoadingEffectTime}"{/if}
        data-src="{$sArticle.image.thumbnails[$imageSize].source}"
        data-srcset="{$sArticle.image.thumbnails[$imageSize].sourceSet}"
        alt="{$desc}"
        title="{$desc|truncate:160}"
    	/>
    </picture>
    {block name='frontend_listing_box_article_image_picture_element_no_script'}
        <noscript>{$smarty.block.parent}</noscript>
    {/block}
{/block}
```
