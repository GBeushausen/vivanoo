import template from './sw-cms-el-preview-accordion-element.html.twig';
import './sw-cms-el-preview-accordion-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-accordion-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
