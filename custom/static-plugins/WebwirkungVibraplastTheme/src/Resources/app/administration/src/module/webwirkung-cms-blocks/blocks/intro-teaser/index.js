import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'intro-teaser',
	label: 'sw-cms.blocks.intro-teaser.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-intro-teaser',
	previewComponent: 'sw-cms-preview-intro-teaser',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		title: {
			type: 'text',
			default: {
				config: {
					content: {
						source: 'static',
						value: `<h2>Lorem Ipsum dolor sit amet</h2>`,
					},
				},
			},
		},
		text: {
			type: 'text',
			default: {
				config: {
					content: {
						source: 'static',
						value: `<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat,
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>`
					},
				},
			},
		},
	}
});