import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'table-of-contents',
	label: 'sw-cms.blocks.table-of-contents.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-table-of-contents',
	previewComponent: 'sw-cms-preview-table-of-contents',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		content: 'table-of-contents-element',
	}
});