import template from './sw-cms-el-table-of-contents-element.html.twig';

const {Component} = Shopware;

Component.register('sw-cms-el-table-of-contents-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('table-of-contents-element');
        }
    },
});
