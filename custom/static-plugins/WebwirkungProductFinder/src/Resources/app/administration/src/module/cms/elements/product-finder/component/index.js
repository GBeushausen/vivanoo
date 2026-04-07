import template from './sw-cms-el-product-finder.html.twig';

const {Component, Mixin, Filter} = Shopware;

Component.register('sw-cms-el-product-finder', {
    template,
    mixins: [
        Mixin.getByName('cms-element'),
    ],

    created() {
        this.createdComponent();
    },
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
    methods: {
        createdComponent() {
            this.initElementConfig('product-finder');
        }
    }
});
