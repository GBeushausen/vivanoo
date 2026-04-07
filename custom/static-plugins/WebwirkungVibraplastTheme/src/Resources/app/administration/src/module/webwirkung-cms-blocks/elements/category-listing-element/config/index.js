import template from './sw-cms-el-config-category-listing-element.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria, EntityCollection } = Shopware.Data;

Component.register('sw-cms-el-config-category-listing-element', {
    template,

    mixins: [
        'cms-element'
    ],
    inject: ['repositoryFactory'],
    
    data() {
        return {
            categoryCollection: null,
            categoryStream: null,
            showCategoryStreamPreview: false,
            
            // Temporary values to store the previous selection in case the user changes the assignment type.
            tempCategoryIds: [],
            tempStreamId: null,
        };
    },
    
    computed: {
        categoryRepository() {
            return this.repositoryFactory.create('category');
        },
        
        categories() {
            if (this.element?.data?.categories && this.element.data.categories.length > 0) {
                return this.element.data.categories;
            }
            
            return null;
        },
        
        columns: {
            get() {
                return this.element.config.columns.value;
            },
            
            set(value) {
                this.element.config.columns.value = value;
            }
        },
        
        categoryMultiSelectContext() {
            const context = { ...Shopware.Context.api };
            context.inheritance = true;
            
            return context;
        },
    },
    
    created() {
        this.createdComponent();
    },
    
    methods: {
        createdComponent() {
            this.initElementConfig('category-listing-element');
            
            this.categoryCollection = new EntityCollection('/category', 'category', Shopware.Context.api);
            
            if (this.element.config.categories.value.length <= 0) {
                return;
            }
            
            // We have to fetch the assigned entities again
            // ToDo: Fix with NEXT-4830
            const criteria = new Criteria(1, 100);
            criteria.setIds(this.element.config.categories.value);
            
            this.categoryRepository
              .search(criteria, { ...Shopware.Context.api, inheritance: true })
              .then((result) => {
                  this.categoryCollection = result;
              });
        },
        
        onChangeAssignmentType(type) {
            this.tempStreamId = this.element.config.categories.value;
            this.element.config.categories.value = this.tempCategoryIds;
        },
        
        onCategoriesChange() {
            this.element.config.categories.value = this.categoryCollection.getIds();
            
            if (!this.element?.data) {
                return;
            }
            
            this.$set(this.element.data, 'categories', this.categoryCollection);
        },
        
        isSelected(itemId) {
            return this.categoryCollection.has(itemId);
        },
    },
});
