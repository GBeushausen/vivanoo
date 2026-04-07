import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'faq-select-element',
    label: 'faq.elements.select.label',
    component: 'sw-cms-el-faq-select-element',
    configComponent: 'sw-cms-el-config-faq-select-element',
    previewComponent: 'sw-cms-el-preview-faq-select-element',
    defaultConfig: {
        faqEntry: {
            source: 'static',
            value: [],
            entity: {
                name: 'ww_faq',
            }
        }
    }
});
