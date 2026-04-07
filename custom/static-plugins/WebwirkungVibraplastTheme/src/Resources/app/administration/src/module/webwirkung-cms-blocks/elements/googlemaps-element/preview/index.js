import template from './sw-cms-el-preview-googlemaps-element.html.twig';
import './sw-cms-el-preview-googlemaps-element.scss';

const { Component, Filter } = Shopware

Component.register('sw-cms-el-preview-googlemaps-element', {
	template,
	
	computed: {
		assetFilter() {
			return Filter.getByName('asset');
		},
	},
});