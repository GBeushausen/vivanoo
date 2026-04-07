import template from './config/sw-cms-el-config-blog.html.twig'
const { Component } = Shopware;

Component.override('sw-cms-el-config-blog', {
	template
});

// set js timeout
setTimeout(() => {
	let blogConfig = Shopware.Service('cmsService').getCmsElementConfigByName('blog');

	blogConfig.defaultConfig.hideImage = {
		source: 'static',
		value: false
	}
	blogConfig.defaultConfig.showDate = {
		source: 'static',
		value: false
	}
	blogConfig.defaultConfig.filterAside = {
		source: 'static',
		value: false
	}
	blogConfig.defaultConfig.firstElBig = {
		source: 'static',
		value: false
	}
	blogConfig.defaultConfig.showTeaser = {
		source: 'static',
		value: false
	}

	Shopware.Service('cmsService').registerCmsElement(blogConfig);
}, 1000);


