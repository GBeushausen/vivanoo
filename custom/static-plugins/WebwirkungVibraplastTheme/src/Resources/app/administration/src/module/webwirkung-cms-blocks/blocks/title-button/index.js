import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'title-button',
	label: 'sw-cms.blocks.title-button.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-title-button',
	previewComponent: 'sw-cms-preview-title-button',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		left: {
			type: 'text',
			default: {
				config: {
					content: {
						source: 'static',
						value: `<h1>{{ category.translated.name }}</h1>`
					},
				},
			},
		},
		right: {
			type: 'text',
			default: {
				config: {
					content: {
						source: 'static',
						value: `<a class="btn btn-primary btn-sm" href="#" target="_self" rel="noreferrer noopener">Button Text</a>`
					},
				},
			},
		},
	}
});