import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'glossary-listing',
    label: 'webwirkung.glossary.blocks.glossary.listing.label',
    category: 'webwirkung-glossary-blocks',
    component: 'sw-cms-block-glossary-listing',
    previewComponent: 'sw-cms-preview-glossary-listing',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'boxed'
    },
    slots: {
        listing: 'glossary-listing',
    },
});
