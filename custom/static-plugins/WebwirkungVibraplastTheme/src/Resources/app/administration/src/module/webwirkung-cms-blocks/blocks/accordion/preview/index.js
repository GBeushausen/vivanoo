import template from './sw-cms-preview-accordion.html.twig';
import './sw-cms-preview-accordion.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-accordion', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
