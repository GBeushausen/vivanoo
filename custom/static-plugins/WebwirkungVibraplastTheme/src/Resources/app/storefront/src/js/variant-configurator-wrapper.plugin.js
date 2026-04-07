import Plugin from 'src/plugin-system/plugin.class'
import HttpClient from 'src/service/http-client.service';

export default class VariantConfiguratorWrapperPlugin extends Plugin {
    init() {
        this.htmlCache = {};
        this.client = new HttpClient();
        this.endpoint = this.options.endpoint;
        this.container = this.el.querySelector('.variant-configurator-wrapper');
        this.variantTableSelect = document.querySelector('#variantsTableSelect');
        this.initListeners();
        this.fetchConfigurator();
    }

    initListeners() {
        if (this.variantTableSelect) {
            this.variantTableSelect.addEventListener('change', (e) => {
                this.fetchConfigurator(e.target.value);
            });
        }
    }

    fetchConfigurator(value) {
        if (!this.variantTableSelect) {
            return;
        }

        const selection = value || this.variantTableSelect.value;

        if (selection in this.htmlCache) {
            this.fillConfiguratorContent(this.htmlCache[selection]);
            return;
        }

        this.client.get(this.endpoint + '?variantPropertyOption=' + selection, (response) => {
            this.htmlCache[selection] = response;
            this.fillConfiguratorContent(response)
        })
    }

    fillConfiguratorContent(content){
        this.container.innerHTML = content;
        window.PluginManager.initializePlugins();

        const variantsConfiguratorOptionSelect = this.container.querySelector('#variantsConfiguratorOptionSelect');
        if (variantsConfiguratorOptionSelect) {
            variantsConfiguratorOptionSelect.addEventListener('change', (e) => {
                this.fetchConfigurator(e.target.value);
            });
        }
    }

}