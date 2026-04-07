import template from './sw-cms-el-icon-teaser-element.html.twig';
import './sw-cms-el-icon-teaser-element.scss';

const {Component} = Shopware;

Component.register('sw-cms-el-icon-teaser-element', {
    template,

    mixins: [
        'cms-element'
    ],
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('icon-teaser-element');
        },
        onInput() {
            this.emitChanges();
        },
        emitChanges(content) {
            this.$emit('element-update', this.element);
        },
    },
});
