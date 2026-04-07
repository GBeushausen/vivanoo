import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'double-image-element',
    label: 'sw-cms.elements.double-image-element.label',
    component: 'sw-cms-el-double-image-element',
    configComponent: 'sw-cms-el-config-double-image-element',
    previewComponent: 'sw-cms-el-preview-double-image-element',
    defaultConfig: {
        overlayImageOne: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        overlayImageTwo: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        backgroundImage: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
    }
});
