$.subscribe('plugin/swAjaxProductNavigation/onProductNavigationFinished', function (event, plugin, response) {
    if (!froshWebPSupported()) {
        return;
    }

    var $prevBtn = plugin.$prevButton,
        $nextBtn = plugin.$nextButton;

    if (response.previousProduct && response.previousProduct.image.thumbnails[0].attributes.webp) {
        $prevBtn.find(plugin.opts.imageContainerSelector).css('background-image', 'url(' + response.previousProduct.image.thumbnails[0].attributes.webp.thumbnail.source + ')');
    }

    if (response.nextProduct && response.nextProduct.image.thumbnails[0].attributes.webp) {
        $nextBtn.find(plugin.opts.imageContainerSelector).css('background-image', 'url(' + response.nextProduct.image.thumbnails[0].attributes.webp.thumbnail.source + ')');

    }
});
