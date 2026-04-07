import template from './sw-cms-el-banner-element.html.twig';
import './sw-cms-el-banner-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-banner-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('banner-element');
        }
    },
});
