import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'blog-similar-listing',
    label: 'sas-blog.blocks.blog.similarListing.label',
    category: 'webwirkung-blocks',
    component: 'sas-cms-block-similar-listing',
    previewComponent: 'sas-cms-preview-similar-listing',
    defaultConfig: {
        marginBottom: '0px',
        marginTop: '0px',
        marginLeft: '0px',
        marginRight: '0px',
        sizingMode: 'boxed',
    },
    slots: {
        listing: 'blog-newest-listing',
    },
});
