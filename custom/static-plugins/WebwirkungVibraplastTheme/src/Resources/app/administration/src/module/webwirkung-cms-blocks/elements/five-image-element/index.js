import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'five-image-element',
    label: 'sw-cms.elements.five-image-element.label',
    component: 'sw-cms-el-five-image-element',
    configComponent: 'sw-cms-el-config-five-image-element',
    previewComponent: 'sw-cms-el-preview-five-image-element',
    defaultConfig: {
        fiveImageOne: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        fiveImageTwo: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        fiveImageThree: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        fiveImageFour: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        fiveImageFive: {
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
