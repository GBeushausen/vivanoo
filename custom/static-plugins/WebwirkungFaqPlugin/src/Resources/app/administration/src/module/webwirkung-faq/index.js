/**
 * User rights
 */
import './acl'

/**
 * Backend Module
 */
import './page/webwirkung-faq-list';
import './page/webwirkung-faq-detail';
import './page/webwirkung-faq-create';

/**
 * CMS Blocks
 * */

import './extension/sw-cms/component/sw-cms-sidebar';

/**
 * CMS Blocks
 */
import './blocks/faq-listing';
import './blocks/faq-select';

/**
 * CMS Elements
 */
import './elements/faq-category-listing';
import './elements/faq-select-element';

/**
 * Translations
 */
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

/**
 * Component (Vertical Menu)
 */
import './component/faq-vertical-tabs';


Shopware.Module.register('webwirkung-faq', {
	type: 'plugin',
	name: 'Webwirkung FAQ Module',
	color: '#ff3d58',
	icon: 'default-shopping-paper-bag-product',
	title: 'Webwirkung FAQ',
	description: 'Hier können die FAQs gepflegt werden.',
	
	snippets: {
		'de-DE': deDE,
		'en-GB': enGB
	},
	
	routes: {
		list: {
			component: 'webwirkung-faq-list',
			path: 'list'
		},
		detail: {
			component: 'webwirkung-faq-detail',
			path: 'detail/:id',
			meta: {
				parentPath: 'webwirkung.faq.list'
			}
		},
		create: {
			component: 'webwirkung-faq-create',
			path: 'create',
			meta: {
				parentPath: 'webwirkung.faq.list'
			}
		}
	},
	
	navigation: [{
		path: 'webwirkung.faq.list',
		parent: 'sw-content',
		label: 'faq.general.mainMenuItemGeneral',
		icon: 'default-shopping-paper-bag-product',
		position: 222
	}],
	
});