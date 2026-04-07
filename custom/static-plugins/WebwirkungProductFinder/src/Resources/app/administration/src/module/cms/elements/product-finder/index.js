import './preview';
import './component';
import './config';

Shopware.Service('cmsService').registerCmsElement({
    name: 'product-finder',
    label: 'WebwirkungProductFinder.cms.elements.productFinder.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-el-product-finder',
    configComponent: 'sw-cms-el-config-product-finder',
    previewComponent: 'sw-cms-el-preview-product-finder',
    defaultConfig: {},
});
