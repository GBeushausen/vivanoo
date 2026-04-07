const PluginManager = window.PluginManager;

PluginManager.register('GlossaryPlugin', () => import('./glossary-plugin/glossary-plugin.plugin'), '[data-glossary-plugin]');
PluginManager.register('GlossaryFilterPlugin', () => import('./glossary-filter-plugin/glossary-filter-plugin.plugin'), '.glossary-alphabet-filter');
