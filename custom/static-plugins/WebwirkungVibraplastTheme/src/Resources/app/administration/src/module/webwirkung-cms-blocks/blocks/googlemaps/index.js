import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'googlemaps',
    label: 'sw-cms.blocks.googlemaps.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-googlemaps',
    previewComponent: 'sw-cms-preview-googlemaps',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'googlemaps-element'
    }
});