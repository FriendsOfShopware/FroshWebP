## WebP Unterstützung für Shopware

### Features

* Einsparung von Bildgrösse gegenüber JPEG / PNG
* Unterstützt verlustfreie Transparenz und Animation
* Durch die Verwendung von `HTML5 Picture tag` werden die Webp-Bilder nur in Browsern angezeigt, die es unterstützen

### Kompatiblität

Um Webp-Bilder generieren zu können, muss entweder PHP mit Webp-Support kompiliert sein oder `cwebp` auf dem Server installiert sein.
Cwebp kann auch via Konsolenbefehl "./bin/console frosh:webp:download-google-binaries" heruntergeladen werden.
Die Unterstützung der Webp-Generierung kann nach der Installation des Plugins im Backend unter `Einstellungen/Systeminfo/Webp-Support` geprüft werden.

Bekannte Hoster, die Webp im Standard unterstützen:

* Timmehosting
* Profihost
* enerSpace

Das Plugin wird von der Github Organization [FriendsOfShopware](https://github.com/FriendsOfShopware/) entwickelt.
Maintainer des Plugin ist [Soner Sayakci](https://github.com/shyim).
Das Github Repository ist zu finden [hier](https://github.com/FriendsOfShopware/FroshWebP)
[Bei Fragen / Fehlern bitte ein Github Issue erstellen](https://github.com/FriendsOfShopware/FroshWebP/issues/new)
