import './component';
import './config';
import './preview';

const Criteria = Shopware.Data.Criteria;
const criteria = new Criteria(1, 25);

Shopware.Service('cmsService').registerCmsElement({
  name: 'sub-category-listing-element',
  label: 'sw-cms.elements.sub-category-listing-element.label',
  component: 'sw-cms-el-sub-category-listing-element',
  configComponent: 'sw-cms-el-config-sub-category-listing-element',
  previewComponent: 'sw-cms-el-preview-sub-category-listing-element',
  defaultConfig: {
    category: {
      source: 'static',
      value: '',
      required: false,
      entity: {
        name: 'category',
        criteria: criteria,
      },
    },
  },
});
