import template from './sw-cms-el-sub-category-listing-element.html.twig';

const { Component } = Shopware;

Component.register('sw-cms-el-sub-category-listing-element', {
  template,

  mixins: ['cms-element'],
  created() {
    this.createdComponent();
  },
  methods: {
    createdComponent() {
      this.initElementConfig('sub-category-listing-element');
    },
  },
});
