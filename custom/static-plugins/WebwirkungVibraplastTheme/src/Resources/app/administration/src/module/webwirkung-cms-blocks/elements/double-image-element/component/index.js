import template from './sw-cms-el-double-image-element.html.twig';
import './sw-cms-el-double-image-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-double-image-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('double-image-element');
        }
    },
});
