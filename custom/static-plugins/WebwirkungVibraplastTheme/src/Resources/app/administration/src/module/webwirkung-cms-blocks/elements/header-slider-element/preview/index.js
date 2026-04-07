import template from './sw-cms-el-preview-header-slider-element.html.twig';
import './sw-cms-el-preview-header-slider-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-header-slider-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
