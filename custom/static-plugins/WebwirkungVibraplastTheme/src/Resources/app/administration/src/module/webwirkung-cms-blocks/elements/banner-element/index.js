import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'banner-element',
    label: 'sw-cms.elements.banner-element.label',
    component: 'sw-cms-el-banner-element',
    configComponent: 'sw-cms-el-config-banner-element',
    previewComponent: 'sw-cms-el-preview-banner-element',
    defaultConfig: {
        bannerText: {
            source: 'static',
            value: '<h3>Verwirklichen Sie Ihre Ideen in einzigartigen Projekten</h3><p>Geben Sie Ihren technischen Konzepten Leben mit unserer individuellen Entwicklung. Bestellen Sie jetzt die Entwicklung eines individuellen Projekts und sehen Sie, wie Ihre Vision Wirklichkeit wird.</p><p><a target="_self" href="#" class="btn btn-secondary">Anfrage einreichen</a></p>',
        },
        bannerImage: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        bannerBackground: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        bannerSize: {
            source: 'static',
            value: 'broad',
        },
        bannerArrangement: {
            source: 'static',
            value: 'text-left',
        },
        
    }
});
