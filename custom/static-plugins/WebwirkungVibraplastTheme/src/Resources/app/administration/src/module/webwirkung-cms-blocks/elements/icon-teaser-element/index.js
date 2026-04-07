import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'icon-teaser-element',
    label: 'sw-cms.elements.icon-teaser-element.label',
    component: 'sw-cms-el-icon-teaser-element',
    configComponent: 'sw-cms-el-config-icon-teaser-element',
    previewComponent: 'sw-cms-el-preview-icon-teaser-element',
    defaultConfig: {
        iconTeaserImage: {
            source: 'static',
            value: null,
            required: false,
            entity: {
                name: 'media',
            },
        },
        iconTeaserBackground: {
            source: 'static',
            value: 'blue'
        },
        iconTeaserText: {
            source: 'static',
            value: '<h4>Glossar</h4><p>Hier werden alle wichtigen Begriffe rund um unsere breite Produktpalette zusammengefasst, thematisiert und leicht verständlich erklärt.</p><a target="_self" href="#" class="btn btn-secondary btn-sm">Glossar ansehen</a>',
        },
        iconBackground: {
            source: 'static',
            value: 'transparent'
        },
        iconPosition: {
            source: 'static',
            value: 'top'
        },
        iconTeaserSmallSpace: {
            source: 'static',
            value: false
        },
        iconTeaserTitleBefore: {
            source: 'static',
            value: ''
        },
    }
});
