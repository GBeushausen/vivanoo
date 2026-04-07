/**
 * User rights
 */
import './acl';

/**
 * Backend Module
 */
import './page/webwirkung-faq-category-list';
import './page/webwirkung-faq-category-detail';
import './page/webwirkung-faq-category-create';


/**
 * Translations
 */
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Module.register('webwirkung-faq-category', {
	type: 'plugin',
	name: 'Webwirkung FAQ Category Module',
	color: '#ff3d58',
	icon: 'default-shopping-paper-bag-product',
	title: 'Webwirkung FAQ Kategorien',
	description: 'Manage your faq categories here.',
	
	snippets: {
		'de-DE': deDE,
		'en-GB': enGB
	},
	
	routes: {
		list: {
			component: 'webwirkung-faq-category-list',
			path: 'list'
		},
		detail: {
			component: 'webwirkung-faq-category-detail',
			path: 'detail/:id',
			meta: {
				parentPath: 'webwirkung.faq.category.list'
			}
		},
		create: {
			component: 'webwirkung-faq-category-create',
			path: 'create',
			meta: {
				parentPath: 'webwirkung.faq.category.list'
			}
		}
	}
});