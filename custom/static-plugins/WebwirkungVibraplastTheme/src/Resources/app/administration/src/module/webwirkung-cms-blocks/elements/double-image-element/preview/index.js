import template from './sw-cms-el-preview-double-image-element.html.twig';
import './sw-cms-el-preview-double-image-element.scss';

const { Component, Filter } = Shopware;

Component.register('sw-cms-el-preview-double-image-element', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});
