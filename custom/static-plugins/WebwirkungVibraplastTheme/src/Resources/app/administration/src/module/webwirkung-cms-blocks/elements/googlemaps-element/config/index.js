import template from './sw-cms-el-config-googlemaps-element.html.twig';

Shopware.Component.register('sw-cms-el-config-googlemaps-element', {
	template,
	
	mixins: [
		'cms-element'
	],
	
	computed: {
		mapsUrl: {
			get() {
				return this.element.config.mapsUrl.value;
			},
			
			set(value) {
				this.element.config.mapsUrl.value = value;
			}
		},
		mapsAddress: {
			get() {
				return this.element.config.mapsAddress.value;
			},
			
			set(value) {
				this.element.config.mapsAddress.value = value;
			}
		},
		mapsPhone: {
			get() {
				return this.element.config.mapsPhone.value;
			},
			
			set(value) {
				this.element.config.mapsPhone.value = value;
			}
		},
		mapsEmail: {
			get() {
				return this.element.config.mapsEmail.value;
			},
			
			set(value) {
				this.element.config.mapsEmail.value = value;
			}
		}
	},
	
	created() {
		this.createdComponent();
	},
	
	methods: {
		createdComponent() {
			this.initElementConfig('googlemaps-element');
		},
		
		onElementUpdate() {
			this.$emit('element-update', this.element);
		}
	}
});