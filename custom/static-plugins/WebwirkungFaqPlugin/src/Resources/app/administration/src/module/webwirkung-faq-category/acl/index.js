Shopware.Service('privileges').addPrivilegeMappingEntry({
	category: 'permissions',
	parent: null,
	key: 'ww_faq_category',
	roles: {
		viewer: {
			privileges: [
				'ww_faq_category:read',
			],
			dependencies: []
		},
		editor: {
			privileges: [
				'ww_faq_category:update',
			],
			dependencies: []
		},
		creator: {
			privileges: [
				'ww_faq_category:create',
			],
			dependencies: []
		},
		deleter: {
			privileges: [
				'ww_faq_category:delete',
			],
			dependencies: []
		}
	}
});