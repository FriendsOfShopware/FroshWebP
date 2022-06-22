$.overridePlugin('swImageGallery', {
    init: function() {
        if (!document.documentElement.classList.contains('has--webp')) {
            this.$el.find('.image--element[data-img-original]').each(function (el) {
                $el = $(this);

                $el.attr('data-img-original', $el.attr('data-img-webp-original'));
            })
        }

        return this.superclass.init.apply(this, arguments);
    },

    createTemplate: function () {
        var me = this,
            $el;

        if (!document.documentElement.classList.contains('has--webp')) {
            me._$imageContainerClone.find('span[data-img-original]').each(function (i, el) {
                $el = $(el);

                $el.attr('data-img-original', $el.attr('data-img-webp-original'));
            });
        }

        return me.superclass.createTemplate.apply(this, arguments);
    }
});
