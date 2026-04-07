import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'hero-header',
	label: 'sw-cms.blocks.hero-header.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-hero-header',
	previewComponent: 'sw-cms-preview-hero-header',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		header: 'hero-header-element',
	}
});