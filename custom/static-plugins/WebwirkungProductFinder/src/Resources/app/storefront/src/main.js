const PluginManager = window.PluginManager;
PluginManager.register('ProductFinder', () => import('./plugins/product-finder.plugin'), '[data-product-finder]');

