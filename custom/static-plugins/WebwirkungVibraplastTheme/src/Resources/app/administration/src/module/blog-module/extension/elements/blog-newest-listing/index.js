import template from './config/sas-cms-el-config-newest-listing.html.twig'
const { Component } = Shopware;

Component.override('sas-cms-el-config-newest-listing', {
    template
});

// set js timeout
setTimeout(() => {
    let blogNewestConfig = Shopware.Service('cmsService').getCmsElementConfigByName('blog-newest-listing');
    
    blogNewestConfig.defaultConfig.hideImage = {
        source: 'static',
        value: false
    }
    blogNewestConfig.defaultConfig.showDate = {
        source: 'static',
        value: false
    }
    blogNewestConfig.defaultConfig.infinityLoop = {
        source: 'static',
        value: true
    }
    
    Shopware.Service('cmsService').registerCmsElement(blogNewestConfig);
}, 1000);


