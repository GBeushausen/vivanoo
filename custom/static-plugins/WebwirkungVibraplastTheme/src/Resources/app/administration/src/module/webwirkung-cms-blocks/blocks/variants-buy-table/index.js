Shopware.Component.register('sw-cms-block-preview-variants-buy-table', () => import('./preview'));

Shopware.Component.register('sw-cms-block-variants-buy-table', () => import('./component'));

Shopware.Service('cmsService').registerCmsBlock({
    name: 'variants-buy-table',
    label: 'sw-cms.blocks.variants-buy-table.label',
    category: 'webwirkung-blocks',
    component: 'sw-cms-block-variants-buy-table',
    previewComponent: 'sw-cms-block-preview-variants-buy-table',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        content: 'variants-buy-table'
    }
});
