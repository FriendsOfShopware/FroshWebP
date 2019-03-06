$.overridePlugin('swImageGallery', {
    createTemplate: function () {
        var me = this,
            $el;

        if (Modernizr.webp) {
            me._$imageContainerClone.find('span[data-img-original]').each(function (i, el) {
                $el = $(el);

                $el.attr('data-img-original', $el.attr('data-img-webp-original'));
            });
        }

        return me.superclass.createTemplate.apply(this, arguments);
    }
});