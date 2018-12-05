# WebP Support for Shopware

[WebP Browser-Support](http://caniuse.com/#search=webp)

This plugin generates additional webp Thumbnails and uses is in various locations like detail, listing, emotion as preferred type using html5 picture tag. Browsers which does not support webp, will get normally jpg. 
**After installation of the Plugin regenerating all thumbnails is required.**

# Requirements

* Shopware Version 5.3+
* any webp encoder
  * php-gd with webp support
  * cwebp executable in PATH

# Installation

* Checkout Plugin in `/custom/plugins/ShyimWebP`
* Download google binaries if neccessary `php bin/console shyim:webp:download-google-binaries`
* Generate all Thumbnails new with ``php bin/console sw:thumbnail:generate -f``