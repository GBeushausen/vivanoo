import template from './sw-cms-el-variants-buy-table.html.twig';

const {Mixin} = Shopware;

export default {
    template,
    mixins: [
        Mixin.getByName('cms-element'),
    ],

    computed: {
        pageType() {
            return this.cmsPageState?.currentPage?.type ?? '';
        },

        isProductPageType() {
            return this.pageType === 'product_detail';
        },
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.initElementConfig('variants-buy-table');
            this.$set(this.element, 'locked', this.isProductPageType);
        }
    },
    watch: {
        pageType(newPageType) {
            this.$set(this.element, 'locked', newPageType === 'product_detail');
        },
    },
};
