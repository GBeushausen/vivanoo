import template from './sw-cms-preview-hero-header.html.twig';
import './sw-cms-preview-hero-header.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-hero-header', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});