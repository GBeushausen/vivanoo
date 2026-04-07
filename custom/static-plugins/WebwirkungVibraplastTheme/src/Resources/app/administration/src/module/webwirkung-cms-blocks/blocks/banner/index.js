import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'banner',
    label: 'sw-cms.blocks.banner.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-banner',
    previewComponent: 'sw-cms-preview-banner',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'banner-element'
    }
});