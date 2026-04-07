import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: '5-7-column',
	label: 'sw-cms.blocks.5-7-column.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-5-7-column',
	previewComponent: 'sw-cms-preview-5-7-column',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		left: 'icon-teaser-element',
		right: 'text',
	}
});