import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'image-caption',
	label: 'sw-cms.blocks.image-caption.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-image-caption',
	previewComponent: 'sw-cms-preview-image-caption',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		content: 'image-caption-element'
	}
});