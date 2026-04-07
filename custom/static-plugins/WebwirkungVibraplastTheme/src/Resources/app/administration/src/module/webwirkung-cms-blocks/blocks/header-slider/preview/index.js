import template from './sw-cms-preview-header-slider.html.twig';
import './sw-cms-preview-header-slider.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-header-slider', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});