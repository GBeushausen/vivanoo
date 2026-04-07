import './sw-cms-section.scss';

Shopware.Component.override('sw-cms-section', {
	computed: {
		// Add narrow class to section sizingmode
    sectionClasses() {
        return {
            'is--active': this.active,
            'is--boxed': this.section.sizingMode === 'boxed',
            'is--narrow': this.section.sizingMode === 'narrow',
        };
    },
	}
});
