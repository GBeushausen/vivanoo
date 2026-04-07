import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'history',
    label: 'sw-cms.blocks.history.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-history',
    previewComponent: 'sw-cms-preview-history',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'history-element'
    }
});