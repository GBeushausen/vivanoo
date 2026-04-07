import template from './sw-cms-el-label-text-button.html.twig';
import './sw-cms-el-label-text-button.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-label-text-button', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('label-text-button');
        },
        onInput() {
            this.emitChanges();
        },
        emitChanges(content) {
            this.$emit('element-update', this.element);
        },
    },
});
