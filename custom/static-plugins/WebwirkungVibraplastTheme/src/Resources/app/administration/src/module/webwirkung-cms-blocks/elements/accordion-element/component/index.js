import template from './sw-cms-el-accordion-element.html.twig';
import './sw-cms-el-accordion-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-accordion-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('accordion-element');
        }
    },
});
