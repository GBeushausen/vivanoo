import template from './sw-cms-el-five-image-element.html.twig';
import './sw-cms-el-five-image-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-five-image-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('five-image-element');
        }
    },
});
