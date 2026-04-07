import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'faq-select',
    label: 'faq.blocks.faq.select.label',
    category: 'webwirkung-faq-blocks',
    component: 'sw-cms-block-faq-select',
    previewComponent: 'sw-cms-preview-faq-select',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'boxed'
    },
    slots: {
        'manyEntries': {
            type: 'faq-select-element',
            default: {
                config: {
                    faqEntry: { source: 'static', value: [] }
                }
            }
        }
    }
});
