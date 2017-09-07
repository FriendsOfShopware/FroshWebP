# WebP Support for Shopware

Required Minimum Shopware Version 5.3

[WebP Browser-Support](http://caniuse.com/#search=webp)

This plugin generates additional webp Thumbnails and uses is in various locations like detail, listing, emotion as preferred type using html5 picture tag. Browsers which does not support webp, will get normally jpg. 
**After installation of the Plugin regenerating all thumbnails is required.**

# Installation

* Checkout Plugin in `/custom/plugins/ShyimWebP`
* Generate all Thumbnails new with ``php bin/console sw:thumbnail:generate -f``