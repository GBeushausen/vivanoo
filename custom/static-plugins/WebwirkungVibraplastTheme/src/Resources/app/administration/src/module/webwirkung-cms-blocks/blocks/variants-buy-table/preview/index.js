import template from './sw-cms-block-preview-variants-buy-table.html.twig';
import './sw-cms-block-preview-variants-buy-table.scss';

const { Filter } = Shopware;
export default {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
};
