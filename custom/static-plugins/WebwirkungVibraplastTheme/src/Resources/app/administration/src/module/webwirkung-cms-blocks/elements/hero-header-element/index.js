import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'hero-header-element',
    label: 'sw-cms.elements.hero-header-element.label',
    component: 'sw-cms-el-hero-header-element',
    configComponent: 'sw-cms-el-config-hero-header-element',
    previewComponent: 'sw-cms-el-preview-hero-header-element',
    defaultConfig: {
        heroTitle: {
            source: 'static',
            value: 'Willkommen Ihre Industriekomponenten-Plattform',
        },
        heroText: {
            source: 'static',
            value: '<p>Entdecken Sie innovative Produkte für maßgeschneiderten Schutz und effektive Reduzierung von Vibrationen, Stößen und Schall.</p><p><a target="_self" href="#" class="btn btn-primary">Digitaler Kundenberater</a>&nbsp;<a href="#" class="btn btn-secondary">Katalog ansehen</a></p>',
        },
        heroImage: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        heroBackground: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
    }
});
