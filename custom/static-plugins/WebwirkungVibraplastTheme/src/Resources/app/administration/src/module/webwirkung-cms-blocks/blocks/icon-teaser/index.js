import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'icon-teaser',
	label: 'sw-cms.blocks.icon-teaser.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-icon-teaser',
	previewComponent: 'sw-cms-preview-icon-teaser',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		left: 'icon-teaser-element',
		right: 'icon-teaser-element',
	}
});