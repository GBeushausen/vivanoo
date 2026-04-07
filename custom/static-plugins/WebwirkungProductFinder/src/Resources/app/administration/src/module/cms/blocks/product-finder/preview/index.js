import template from './sw-cms-preview-product-finder.html.twig';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-product-finder', {
    template,
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
