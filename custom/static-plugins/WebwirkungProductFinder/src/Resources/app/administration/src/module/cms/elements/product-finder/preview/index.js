import template from './sw-cms-el-preview-product-finder.html.twig';
import './sw-cms-el-preview-product-finder.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-product-finder', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
