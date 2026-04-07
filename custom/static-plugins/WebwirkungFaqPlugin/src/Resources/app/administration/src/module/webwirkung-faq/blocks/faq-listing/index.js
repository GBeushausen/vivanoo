import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'faq-listing',
    label: 'faq.blocks.faq.listing.label',
    category: 'webwirkung-faq-blocks',
    component: 'sw-cms-block-faq-listing',
    previewComponent: 'sw-cms-preview-faq-listing',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'boxed'
    },
    slots: {
        listing: 'faq-category-listing',
    },
});
