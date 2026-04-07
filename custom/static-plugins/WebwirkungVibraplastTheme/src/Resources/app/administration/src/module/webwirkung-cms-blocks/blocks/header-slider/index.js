import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'header-slider',
    label: 'sw-cms.blocks.header-slider.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-header-slider',
    previewComponent: 'sw-cms-preview-header-slider',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'header-slider-element'
    }
});