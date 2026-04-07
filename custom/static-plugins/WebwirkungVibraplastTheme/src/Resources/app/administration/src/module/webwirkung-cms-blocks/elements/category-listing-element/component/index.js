import template from './sw-cms-el-category-listing-element.html.twig';

const {Component} = Shopware;

Component.register('sw-cms-el-category-listing-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('category-listing-element');
        }
    },
});
