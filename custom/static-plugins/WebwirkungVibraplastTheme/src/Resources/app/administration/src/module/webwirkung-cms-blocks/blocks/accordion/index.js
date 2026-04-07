import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'accordion',
    label: 'sw-cms.blocks.accordion.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-accordion',
    previewComponent: 'sw-cms-preview-accordion',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'accordion-element'
    }
});
