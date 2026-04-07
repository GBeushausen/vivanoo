import template from './sw-cms-preview-banner.html.twig';
import './sw-cms-preview-banner.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-banner', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});