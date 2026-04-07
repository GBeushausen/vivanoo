import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
	name: 'icon-teaser-three-col',
	label: 'sw-cms.blocks.icon-teaser-three-col.label',
	category: 'webwirkung-blocks',
	component: 'sw-cms-block-icon-teaser-three-col',
	previewComponent: 'sw-cms-preview-icon-teaser-three-col',
	defaultConfig: {
		marginBottom: '0',
		marginTop: '0',
		marginLeft: '0',
		marginRight: '0',
		sizingMode: 'boxed'
	},
	slots: {
		left: {
			type: 'icon-teaser-element',
			default: {
				config: {
					iconTeaserBackground: {
						source: 'static',
						value: 'grey'
					},
					iconTeaserText: {
						source: 'static',
						value: `<h5>Sofort kaufen</h5><p>Standardisierte Produkte direkt erwerben</p>`
					},
				},
			},
		},
		center: {
			type: 'icon-teaser-element',
			default: {
				config: {
					iconTeaserBackground: {
						source: 'static',
						value: 'grey'
					},
					iconTeaserText: {
						source: 'static',
						value: `<h5>Schnell konfigurieren</h5><p>Maßgeschneiderte Zuschnitte in wenigen Tagen erhalten</p>`
					},
				},
			},
		},
		right: {
			type: 'icon-teaser-element',
			default: {
				config: {
					iconTeaserBackground: {
						source: 'static',
						value: 'grey'
					},
					iconTeaserText: {
						source: 'static',
						value: `<h5>Individuelle Anpassungen</h5><p>Spezialanfertigungen im Shop anfragen</p>`
					},
				},
			},
		},
	}
});