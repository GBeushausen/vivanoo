import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'product-finder',
    label: 'WebwirkungProductFinder.cms.blocks.productFinder.label',
    category: 'webwirkung-product-finder-blocks',
    component: 'sw-cms-block-product-finder',
    previewComponent: 'sw-cms-preview-product-finder',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'boxed'
    },
    slots: {
        listing: 'product-finder',
    },
});
