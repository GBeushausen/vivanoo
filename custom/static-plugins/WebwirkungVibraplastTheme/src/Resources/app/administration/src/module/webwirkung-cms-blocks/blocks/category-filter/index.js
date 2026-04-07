import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'category-filter',
	label: 'sw-cms.blocks.category-filter.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-category-filter',
	previewComponent: 'sw-cms-preview-category-filter',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		content: 'category-filter-element',
	}
});