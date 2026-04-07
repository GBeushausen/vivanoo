import template from './sw-cms-el-preview-image-caption-element.html.twig';
import './sw-cms-el-preview-image-caption-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-image-caption-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
