import template from './sw-cms-el-googlemaps-element.html.twig';
import './sw-cms-el-googlemaps-element.scss';

Shopware.Component.register('sw-cms-el-googlemaps-element', {
	template,
	
	mixins: [
		'cms-element'
	],
	
	computed: {
		mapsUrl() {
			return `${this.element.config.mapsUrl.value.url}`;
		},
		mapsText() {
			return `${this.element.config.mapsUrl.value.text}`;
		}
	},
	
	created() {
		this.createdComponent();
	},
	
	methods: {
		createdComponent() {
			this.initElementConfig('googlemaps-element');
		}
	}
});