import template from './sw-cms-el-config-sub-category-listing-element.html.twig';

const { Component } = Shopware;
const { Criteria, EntityCollection } = Shopware.Data;

Component.register('sw-cms-el-config-sub-category-listing-element', {
  template,

  mixins: ['cms-element'],
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
    
    category: {
      get() {
        return this.element.config.category.value;
      },

      set(value) {
        this.element.config.category.value = value;
      },
    },
  },

  created() {
    this.createdComponent();
  },

  methods: {
    createdComponent() {
      this.initElementConfig('sub-category-listing-element');

      this.categoryCollection = new EntityCollection(
        '/category',
        'category',
        Shopware.Context.api,
      );

      if (this.element.config.category.value.length <= 0) {
        return;
      }

      if (!Array.isArray(this.element.config.category.value) || this.element.config.category.value.length === 0) {
        return;
      }
      
      const criteria = new Criteria(1, 100);
      criteria.setIds(this.element.config.category.value);

      this.categoryRepository
        .search(criteria, { ...Shopware.Context.api, inheritance: true })
        .then((result) => {
          this.categoryCollection = result;
        });
    },

    onCategoriesChange() {
      this.element.config.category.value = this.categoryCollection.getIds();

      if (!this.element?.data) {
        return;
      }

      this.$set(this.element.data, 'category', this.categoryCollection);
    },

    isSelected(itemId) {
      return this.categoryCollection.has(itemId);
    },
  },
});
