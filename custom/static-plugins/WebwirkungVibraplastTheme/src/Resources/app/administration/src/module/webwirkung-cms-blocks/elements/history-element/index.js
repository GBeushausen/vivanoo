import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'history-element',
    label: 'sw-cms.elements.history-element.label',
    component: 'sw-cms-el-history-element',
    configComponent: 'sw-cms-el-config-history-element',
    previewComponent: 'sw-cms-el-preview-history-element',
    defaultConfig: {
        historyItems: {
            source: 'static',
            value: [
                {
                    active: true,
                    contentType: 'default',
                    sort: 0,
                    year: '2024',
                    text: '<ul><li>Fertigstellung Überbauung "Ifang" Aadorf der Dutly Immobilien AG mit 12 Mietwohungen (8 Einheiten à 4 1/2 Zimmer und 4 Einheiten Attika à 3 1/2 Zimmer)</li></ul>',
                    historyImage: null
                },
            ],
        }
    }
});
