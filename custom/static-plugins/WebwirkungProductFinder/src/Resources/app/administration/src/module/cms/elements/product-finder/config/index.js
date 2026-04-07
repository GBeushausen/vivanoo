import template from './sw-cms-el-config-product-finder.html.twig';

const {Component, Mixin} = Shopware;

Component.register('sw-cms-el-config-product-finder', {
    template,
    mixins: [
        Mixin.getByName('cms-element')
    ]
});
