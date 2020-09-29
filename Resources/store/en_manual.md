* Install the plugin and activate it
* Clear the cache
* **Generate thumbnails for all Albums (./bin/console sw:thumbnail:generate -f)**
* **Generate for all orginal images in webp (./bin/console frosh:webp:generate)**
* Enable webp in Plugin settings
* Clear the cache

If transparent images cannot be generated it is due to the outdated libwebp version, it is recommended to update PHP \ with a newer libwebp version.
