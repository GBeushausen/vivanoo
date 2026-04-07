import './component';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'glossary-listing',
    label: 'webwirkung.glossary.elements.glossary.listing.label',
    component: 'sw-cms-el-glossary-listing',
    previewComponent: 'sw-cms-el-preview-glossary-listing'
});
