import './component';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'category-filter-element',
    label: 'sw-cms.elements.category-filter-element.label',
    component: 'sw-cms-el-category-filter-element',
    previewComponent: 'sw-cms-el-preview-category-filter-element'
});
