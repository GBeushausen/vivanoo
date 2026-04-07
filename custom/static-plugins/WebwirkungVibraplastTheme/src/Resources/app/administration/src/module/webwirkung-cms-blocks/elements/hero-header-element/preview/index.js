import template from './sw-cms-el-preview-hero-header-element.html.twig';
import './sw-cms-el-preview-hero-header-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-hero-header-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
