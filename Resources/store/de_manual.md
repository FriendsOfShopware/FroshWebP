* Plugin installieren und aktivieren
* Cache leeren
* **Alle Thumbnails neu generieren (./bin/console sw:thumbnail:generate -f)**
* **Alle Bilder als Webp generieren (./bin/console frosh:webp:generate)**
* Webp in den Plugineinstellungen aktivieren
* Cache leeren

Wenn transparente Bilder nicht erzeugt werden können liegt es an der veralteten libwebp Version, hier empfiehlt es sich PHP zuaktualisieren \ mit einer neueren libwebp Version zu compilen.
