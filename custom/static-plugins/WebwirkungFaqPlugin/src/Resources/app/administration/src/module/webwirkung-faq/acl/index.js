Shopware.Service('privileges').addPrivilegeMappingEntry({
	category: 'permissions',
	parent: null,
	key: 'ww_faq',
	roles: {
		viewer: {
			privileges: [
				'ww_faq:read'
			],
			dependencies: []
		},
		editor: {
			privileges: [
				'ww_faq:update'
			],
			dependencies: []
		},
		creator: {
			privileges: [
				'ww_faq:create'
			],
			dependencies: []
		},
		deleter: {
			privileges: [
				'ww_faq:delete'
			],
			dependencies: []
		}
	}
});