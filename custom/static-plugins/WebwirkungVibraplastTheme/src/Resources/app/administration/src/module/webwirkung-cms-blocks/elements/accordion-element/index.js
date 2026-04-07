import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'accordion-element',
    label: 'sw-cms.elements.accordion-element.label',
    component: 'sw-cms-el-accordion-element',
    configComponent: 'sw-cms-el-config-accordion-element',
    previewComponent: 'sw-cms-el-preview-accordion-element',
    defaultConfig: {
        accordionTitle: {
            source: 'static',
            value: 'Titel',
        },
        accordionText: {
            source: 'static',
            value: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>',
        },
    }
});
