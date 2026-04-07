import template from './sw-cms-el-category-filter-element.html.twig';

const {Component} = Shopware;

Component.register('sw-cms-el-category-filter-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('category-filter-element');
        }
    },
});
