import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
  name: 'sub-category-listing',
  label: 'sw-cms.blocks.sub-category-listing.label',
  category: 'webwirkung-blocks',
  component: 'sw-cms-block-sub-category-listing',
  previewComponent: 'sw-cms-preview-sub-category-listing',
  defaultConfig: {
    marginBottom: '0',
    marginTop: '0',
    marginLeft: '0',
    marginRight: '0',
    sizingMode: 'boxed',
  },
  slots: {
    content: 'sub-category-listing-element',
  },
});
