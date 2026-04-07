import template from './sw-cms-el-preview-five-image-element.html.twig';
import './sw-cms-el-preview-five-image-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-five-image-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
