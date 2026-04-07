import template from './sw-cms-el-faq-category-listing.html.twig';
import './sw-cms-el-faq-category-listing.scss';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-el-faq-category-listing', {
    template,
    
    mixins: [
        Mixin.getByName('cms-element')
    ],
    
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('faq-category-listing');
            this.initElementData('faq-category-listing');
        },
    },
});
