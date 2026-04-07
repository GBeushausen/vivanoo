Shopware.Service('privileges').addPrivilegeMappingEntry({
	category: 'permissions',
	parent: null,
	key: 'ww_glossary',
	roles: {
		viewer: {
			privileges: [
				'ww_glossary:read'
			],
			dependencies: []
		},
		editor: {
			privileges: [
				'ww_glossary:update'
			],
			dependencies: []
		},
		creator: {
			privileges: [
				'ww_glossary:create'
			],
			dependencies: []
		},
		deleter: {
			privileges: [
				'ww_glossary:delete'
			],
			dependencies: []
		}
	}
});