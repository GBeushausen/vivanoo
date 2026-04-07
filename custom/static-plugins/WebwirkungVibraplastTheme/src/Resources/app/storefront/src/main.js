const PluginManager = window.PluginManager;
PluginManager.override('GallerySlider', () => import('./js/grid-gallery-slider.plugin'), '[data-gallery-slider]');
PluginManager.register('Navigation', () => import('./js/navigation.plugin'), '.main-navigation');
PluginManager.register('MultiStepForm', () => import('./js/multistep.plugin'), '.has-multistep-navigation');
PluginManager.register('TableOfContentsPlugin', () => import('./js/table-of-contents.plugin'), '[data-table-of-contents-plugin]');
PluginManager.register('HistoryPlugin', () => import('./js/history.plugin'), '[data-history-plugin]');
PluginManager.register('VariantsBuyTable', () => import('./js/variants-buy-table.plugin'), '[data-variants-buy-table]');
PluginManager.register('VariantConfigurator', () => import('./js/variant-configurator.plugin'), '[data-variant-configurator]');
PluginManager.register('VariantConfiguratorWrapper', () => import('./js/variant-configurator-wrapper.plugin'), '[data-variant-configurator-wrapper]');
PluginManager.register('ProductRequestFormModalPlugin', () => import('./js/product-request-form-modal.plugin'), '[data-product-request-form-modal-plugin]');
PluginManager.register('CategoryFilterPlugin', () => import('./js/category-filter.plugin'), '[data-category-filter]');
PluginManager.register('UploadFieldPlugin', () => import('./js/upload-field.plugin'), '[data-upload-field-plugin]');

