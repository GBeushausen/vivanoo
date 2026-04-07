import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
	name: 'googlemaps-element',
	label: 'sw-cms.elements.googlemaps-element.label',
	component: 'sw-cms-el-googlemaps-element',
	configComponent: 'sw-cms-el-config-googlemaps-element',
	previewComponent: 'sw-cms-el-preview-googlemaps-element',
	defaultConfig: {
		mapsUrl: {
			source: 'static',
			value: 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2695.677419906392!2d8.899194476451592!3d47.496197195594874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479a94759763ce97%3A0x4e017d185be5677d!2sVibraplast%20AG!5e0!3m2!1sen!2sch!4v1712674435023!5m2!1sen!2sch',
		},
		mapsAddress: {
			source: 'static',
			value: 'Vibraplast AG Wittenwilerstrasse 25 CH-8355 Aadorf'
		},
		mapsPhone: {
			source: 'static',
			value: '+41 (0) 52 368 00 50'
		},
		mapsEmail: {
			source: 'static',
			value: 'info@vibraplast.ch'
		}
	}
});