import './component';
import './config';
import './preview';

const Criteria = Shopware.Data.Criteria;
const criteria = new Criteria(1, 25);

Shopware.Service('cmsService').registerCmsElement({
    name: 'category-listing-element',
    label: 'sw-cms.elements.category-listing-element.label',
    component: 'sw-cms-el-category-listing-element',
    configComponent: 'sw-cms-el-config-category-listing-element',
    previewComponent: 'sw-cms-el-preview-category-listing-element',
    defaultConfig: {
        categories: {
            source: 'static',
            value: [],
            required: false,
            entity: {
                name: 'category',
                criteria: criteria,
            },
        },
        columns: {
            source: 'static',
            value: "3",
        }
    }
});
