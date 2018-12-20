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
* Run command `composer require frosh/web` and install and active plugin with Plugin Manager 

## After Installation
* Download google binaries if neccessary `php bin/console frosh:webp:download-google-binaries`
* Generate all Thumbnails new with ``php bin/console sw:thumbnail:generate -f``
