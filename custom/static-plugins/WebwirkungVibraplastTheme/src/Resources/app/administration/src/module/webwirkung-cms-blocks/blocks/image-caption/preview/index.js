import template from './sw-cms-preview-image-caption.html.twig';
import './sw-cms-preview-image-caption.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-preview-image-caption', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});