/**
 * User rights
 */
import './acl'

/**
 * CMS Block Category
 * */

import './extension/sw-cms/component/sw-cms-sidebar';

/**
 * Backend module
 */
import './page/webwirkung-glossary-list';
import './page/webwirkung-glossary-detail';
import './page/webwirkung-glossary-create';

/**
 * CMS Blocks
 */
import './blocks/glossary-listing';

/**
 * CMS Elements
 */
import './elements/glossary-listing';

/**
 * Translations
 */
import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Module.register('webwirkung-glossary', {
    type: 'plugin',
    name: 'Glossary',
    title: 'webwirkung.glossary.general.mainMenuItemGeneral',
    description: 'sw-property.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'default-shopping-paper-bag-product',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },
    
    routes: {
        list: {
            component: 'webwirkung-glossary-list',
            path: 'list'
        },
        detail: {
            component: 'webwirkung-glossary-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'webwirkung.glossary.list'
            }
        },
        create: {
            component: 'webwirkung-glossary-create',
            path: 'create',
            meta: {
                parentPath: 'webwirkung.glossary.list'
            }
        }
    },
    
    navigation: [{
        path: 'webwirkung.glossary.list',
        parent: 'sw-content',
        label: 'webwirkung.glossary.general.mainMenuItemGeneral',
        icon: 'default-shopping-paper-bag-product',
        position: 222
    }],
});
