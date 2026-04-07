import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'image-caption-element',
    label: 'sw-cms.elements.image-caption-element.label',
    component: 'sw-cms-el-image-caption-element',
    configComponent: 'sw-cms-el-config-image-caption-element',
    previewComponent: 'sw-cms-el-preview-image-caption-element',
    defaultConfig: {
        imageCaptionImage: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        imageCaption: {
            source: 'static',
            value: '',
        }
    }
});
