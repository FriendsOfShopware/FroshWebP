## WebP Support

### Features

* Savings in image size compared to JPEG / PNG
* Supports lossless transparency and animation
* By HTML5 Picture tag, it is only displayed in browsers that support it

### Compatibility

To generate webp images either PHP with webp support must be built or `cwebp` must be installed on the server.
Cwebp can also be downloaded via console command "./bin/console frosh:webp:download-google-binaries

WebP is not supported by every hoster. Support will be checked during installation.
Advanced: Alternative you can use the WebP Binary to convert files. To download the binary execute ./bin/console frosh:webp:download-google-binaries

Known hosters who can webp in without hassle:

* Timmehosting

The plugin is provided by the Github Organization [FriendsOfShopware](https://github.com/FriendsOfShopware/).
Maintainer of the plugin is [Soner Sayakci](https://github.com/shyim).
You can find the Github Repository [here](https://github.com/FriendsOfShopware/FroshWebP)
[For questions / errors please create a Github Issue](https://github.com/FriendsOfShopware/FroshWebP/issues/new)