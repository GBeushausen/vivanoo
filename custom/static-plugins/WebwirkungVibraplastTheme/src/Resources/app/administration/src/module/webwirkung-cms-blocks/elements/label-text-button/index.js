import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'label-text-button',
    label: 'sw-cms.elements.label-text-button.label',
    component: 'sw-cms-el-label-text-button',
    configComponent: 'sw-cms-el-config-label-text-button',
    previewComponent: 'sw-cms-el-preview-label-text-button',
    defaultConfig: {
        alignment: {
            source: 'static',
            value: 'left'
        },
        label: {
            source: 'static',
            value: 'Über uns'
        },
        longText: {
            source: 'static',
            value: '<h2>Expertise und Tradition seit 60 Jahren</h2><p>Als führendes Produktions- und Handelsunternehmen von technischen Industriekomponenten verfügt die Vibraplast AG über eine über 60-jährige Branchenerfahrung. Unsere Kernkompetenzen liegen in den Bereichen Lärmschutz, Schaumstoffe, Schwingungsisolation sowie technische Gummi- und Kunststoffprodukte.</p><p>In der Vibraplast AG werden alle Prozess- und Logistikstufen konsequent nach ISO 9001 durchgesetzt.</p><p><a target="_self" href="#" class="btn btn-secondary">Mehr lesen</a></p>',
        }
    }
});
