import template from './sw-cms-preview-googlemaps.html.twig';
import './sw-cms-preview-googlemaps.scss';

const {Component, Filter} = Shopware;

Component.register('sw-cms-preview-googlemaps', {
    template,
    
    computed: {
        assetFilter() {
            return Filter.getByName('asset');
        },
    },
});