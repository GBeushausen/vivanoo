import Plugin from 'src/plugin-system/plugin.class';
import ProductFinder from './vue/components/ProductFinder';
import { createApp } from 'vue';

export default class ProductFinderPlugin extends Plugin {
    init() {
        const {config, elementId} = this.options;
        createApp({
            components: {ProductFinder},
            data() {
                return {
                    config,
                }
            },
            template: `
                <ProductFinder
                    :config="config"
                >
                </ProductFinder>`
        }).mount(`#product-finder-${elementId}`);
    }
}
