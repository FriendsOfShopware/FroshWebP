$.subscribe('plugin/swLastSeenProducts/onCreateProductImage', function (_, _, element, data) {
    if (data.images[0].sourceSetWebP) {
        var imageElement = element.find('.image--element');
        var content = imageElement.find('.image--media').html();
        var webPTag = '<source srcset="' + data.images[0].sourceSetWebP + '" type="image/webp">';

        content = '<picture>' + webPTag + content + '</picture>';

        imageElement.html(content);
    }
});
