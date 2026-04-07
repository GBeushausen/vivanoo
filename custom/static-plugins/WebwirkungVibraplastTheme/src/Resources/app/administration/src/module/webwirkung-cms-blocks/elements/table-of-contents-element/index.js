import './component';
import './config';
import './preview';

const Criteria = Shopware.Data.Criteria;
const criteria = new Criteria(1, 25);

Shopware.Service('cmsService').registerCmsElement({
    name: 'table-of-contents-element',
    label: 'sw-cms.elements.table-of-contents-element.label',
    component: 'sw-cms-el-table-of-contents-element',
    configComponent: 'sw-cms-el-config-table-of-contents-element',
    previewComponent: 'sw-cms-el-preview-table-of-contents-element',
    defaultConfig: {
        tableLabel: {
            source: 'static',
            value: "Inhaltsübersicht",
        },
        titleType: {
            source: 'static',
            value: "h4",
        }
    }
});
