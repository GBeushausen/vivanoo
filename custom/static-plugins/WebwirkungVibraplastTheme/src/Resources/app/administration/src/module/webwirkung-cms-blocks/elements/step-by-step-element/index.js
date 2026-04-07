import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'step-by-step-element',
    label: 'sw-cms.elements.step-by-step-element.label',
    component: 'sw-cms-el-step-by-step-element',
    configComponent: 'sw-cms-el-config-step-by-step-element',
    previewComponent: 'sw-cms-el-preview-step-by-step-element',
    defaultConfig: {
        stepByStepItems: {
            source: 'static',
            value: [
                {
                    active: true,
                    contentType: 'default',
                    sort: 0,
                    title: 'Identifizieren Sie die Lärmquelle und den Frequenzbereich',
                    text: '<p>In der Welt von heute, wo Lärm zu einer allgegenwärtigen Herausforderung geworden ist, spielt der Lärmschutz eine entscheidende Rolle. Die Wahl der richtigen Dicke für Lärmschutzanwendungen ist von entscheidender Bedeutung, um eine effektive Schallreduktion zu gewährleisten. In dieser Anleitung werden wir Schritte skizzieren, um die optimale Dicke für Ihren spezifischen Bedarf zu berechnen.</p>',
                },
            ],
        }
    }
});
