import template from './sw-cms-el-preview-banner-element.html.twig';
import './sw-cms-el-preview-banner-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-banner-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
