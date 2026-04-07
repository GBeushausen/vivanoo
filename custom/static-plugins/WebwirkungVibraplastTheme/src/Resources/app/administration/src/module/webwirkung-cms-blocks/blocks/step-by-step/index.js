import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'step-by-step',
    label: 'sw-cms.blocks.step-by-step.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-step-by-step',
    previewComponent: 'sw-cms-preview-step-by-step',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'step-by-step-element'
    }
});