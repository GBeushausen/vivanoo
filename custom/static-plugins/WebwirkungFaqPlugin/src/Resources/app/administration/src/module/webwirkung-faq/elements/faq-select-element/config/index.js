import template from './sw-cms-el-config-faq-select-element.html.twig';

const { Component, Mixin } = Shopware;
const { EntityCollection, Criteria } = Shopware.Data;

Component.register('sw-cms-el-config-faq-select-element', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('cms-element')
    ],

    data() {
        return {
            faqEntry: null,
            selectedEntry: null,
            faqCollection: null
        }
    },
    computed: {
        faqEntryRepository() {
            return this.repositoryFactory.create('ww_faq');
        },
    
        faqMultiSelectContext() {
            const context = Object.assign({}, Shopware.Context.api);
            context.inheritance = true;
        
            return context;
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('faq-select-element');
            this.faqCollection = new EntityCollection('/webwirkung/faq-plugin', 'ww_faq', Shopware.Context.api);
    
            if (this.element.config.faqEntry.value.length <= 0) {
                return;
            }
    
            const criteria = new Criteria(1, 100);
            // criteria.addAssociation('cover');
            // criteria.addAssociation('options.group');
            criteria.setIds(this.element.config.faqEntry.value);
    
            this.faqEntryRepository
              .search(criteria, Object.assign({}, Shopware.Context.api, { inheritance: true }))
              .then((result) => {
                  this.faqCollection = result;
              });
        },
        onFaqChange() {
            this.element.config.faqEntry.value = this.faqCollection.getIds();
        
            if (!this.element?.data) {
                return;
            }
        
            this.$set(this.element.data, 'products', this.faqCollection);
        },
    }
});
