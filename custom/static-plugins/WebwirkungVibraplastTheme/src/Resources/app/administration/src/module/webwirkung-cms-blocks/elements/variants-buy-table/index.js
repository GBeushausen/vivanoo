Shopware.Component.register('sw-cms-el-preview-variants-buy-table', () => import('./preview'));
Shopware.Component.register('sw-cms-el-variants-buy-table', () => import('./component'));

Shopware.Service('cmsService').registerCmsElement({
    name: 'variants-buy-table',
    label: 'sw-cms.elements.variants-buy-table.label',
    component: 'sw-cms-el-variants-buy-table',
    previewComponent: 'sw-cms-el-preview-variants-buy-table',
    disabledConfigInfoTextKey: 'sw-cms.elements.buyBox.infoText.tooltipSettingDisabled',
    collect: Shopware.Service('cmsService').getCollectFunction(),
    defaultConfig: {}
});
