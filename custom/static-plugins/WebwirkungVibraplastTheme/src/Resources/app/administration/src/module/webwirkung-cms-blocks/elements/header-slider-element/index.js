import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'header-slider-element',
    label: 'sw-cms.elements.header-slider-element.label',
    component: 'sw-cms-el-header-slider-element',
    configComponent: 'sw-cms-el-config-header-slider-element',
    previewComponent: 'sw-cms-el-preview-header-slider-element',
    defaultConfig: {
        sliderItems: {
            source: 'static',
            value: [
                {
                    active: true,
                    contentType: 'default',
                    sort: 0,
                    label: 'Bestseller',
                    price: 'Ab 8 CHF /m²',
                    strikePrice: '21 CHF /m²',
                    pricePosition: 'top',
                    text: '<h1>Melaminschaumstoffe aus Basotect® G+</h1><p>Zur Verwendung als Schalldämmung in universellen Anwendungen.</p><a target="_self" href="#" class="btn btn-primary btn-special">Jetzt einkaufen</a>',
                    backgroundMedia: null,
                    overlayMedia: null,
                },
            ],
        }
    }
});
