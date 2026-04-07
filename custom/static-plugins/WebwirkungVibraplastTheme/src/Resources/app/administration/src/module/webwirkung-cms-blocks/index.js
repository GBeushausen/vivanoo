const { Module } = Shopware;

/**
 * Extensions
 */
import './extension/sw-cms/component/sw-cms-sidebar';
import './extension/sw-cms/component/sw-cms-section';
import './extension/sw-cms/component/sw-cms-section/sw-cms-section-config';


/**
 * Language Snippets
 */
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

/**
 * Blocks & Elements
 */

import './blocks/intro-teaser';
import './blocks/title-button';
import './blocks/blog-similar-listing';
import './blocks/5-7-column';

import './blocks/hero-header';
import './elements/hero-header-element';

import './blocks/category-listing';
import './elements/category-listing-element';

import './blocks/category-filter';
import './elements/category-filter-element';

import './blocks/header-slider';
import './elements/header-slider-element';

import './blocks/icon-teaser';
import './blocks/icon-teaser-three-col';
import './elements/icon-teaser-element';

import './elements/label-text-button';
import './elements/double-image-element'
import './elements/five-image-element'

import './blocks/variants-buy-table';
import './elements/variants-buy-table';

import './blocks/table-of-contents';
import './elements/table-of-contents-element';

import './blocks/step-by-step';
import './elements/step-by-step-element';

import './blocks/image-caption';
import './elements/image-caption-element';

import './blocks/history';
import './elements/history-element';

import './blocks/banner';
import './elements/banner-element';

import './blocks/googlemaps';
import './elements/googlemaps-element';

import './blocks/sub-category-listing';
import './elements/sub-category-listing-element';

import './blocks/accordion';
import './elements/accordion-element';

Module.register('webwirkung-cms-blocks', {
    type: 'plugin',
    name: 'Webwirkung CMS BLock',
    title: 'Webwirkung CMS Blocks',
    description: 'Default cms blocks from Webwirkung',
    color: '#F965AF',
    icon: 'default-symbol-content',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },
    
});
