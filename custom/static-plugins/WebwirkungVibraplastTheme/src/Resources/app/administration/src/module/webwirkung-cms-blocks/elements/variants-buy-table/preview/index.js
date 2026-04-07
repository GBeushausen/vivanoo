import template from './sw-cms-el-preview-variants-buy-table.html.twig';
import './sw-cms-el-preview-variants-buy-table.scss';

/**
 * @private
 * @package buyers-experience
 */

const { Filter } = Shopware;
export default {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
};
