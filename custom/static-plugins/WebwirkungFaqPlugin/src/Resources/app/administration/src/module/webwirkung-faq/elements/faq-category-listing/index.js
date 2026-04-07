import './component';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'faq-category-listing',
    label: 'faq.elements.listing.preview.label',
    component: 'sw-cms-el-faq-category-listing',
    previewComponent: 'sw-cms-el-preview-faq-category-listing'
});
