import template from './sw-cms-el-hero-header-element.html.twig';
import './sw-cms-el-hero-header-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-hero-header-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('hero-header-element');
        }
    },
});
