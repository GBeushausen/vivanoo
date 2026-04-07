import template from './sw-cms-el-image-caption-element.html.twig';
import './sw-cms-el-image-caption-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-image-caption-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('image-caption-element');
        },
        onInput() {
            this.emitChanges();
        },
        emitChanges(content) {
            this.$emit('element-update', this.element);
        },
    },
});
