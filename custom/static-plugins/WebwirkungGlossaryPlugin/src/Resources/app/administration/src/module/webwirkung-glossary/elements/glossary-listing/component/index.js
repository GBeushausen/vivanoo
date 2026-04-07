import template from './sw-cms-el-glossary-listing.html.twig';
import './sw-cms-el-glossary-listing.scss';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-el-glossary-listing', {
    template,
    
    mixins: [
        Mixin.getByName('cms-element')
    ],
    
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('glossary-listing');
            this.initElementData('glossary-listing');
        },
    },
});
