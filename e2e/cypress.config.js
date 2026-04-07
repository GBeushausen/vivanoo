const { defineConfig } = require('cypress')
const axios = require('axios')

module.exports = defineConfig({
	e2e: {
		baseUrl: 'https://localhost/',
		// setupNodeEvents(on, config) {
		// 	on('task', {
		// 		async 'db:seed'() {
		// 			// Send request to backend API to re-seed database with test data
		// 			const { data } = await axios.post(`${testDataApiEndpoint}/seed`)
		// 			return data
		// 		},
		// 		//...
		// 	})
		// },
		supportFile: 'cypress/support/e2e.js',
	},
	defaultCommandTimeout: 60000,
	pageLoadTimeout: 120000,
	viewportHeight: 1980,
	viewportWidth: 1920,
	salesChannelName: "Storefront",
	useShopwareTheme: true,
	env: {
		user: 'admin',
		pass: 'shopware',
			salesChannelName: 'Shopware 6 Starter',
		admin: '/admin',
		apiPath: 'api',
		locale: 'en-GB',
		shopwareRoot: '/app',
		localUsage: false,
		usePercy: false,
		minAuthTokenLifetime: 60,
		acceptLanguage: 'en-GB,en;q=0.5',
		dbUser: 'shopware',
		dbPassword: 'shopware',
		dbHost: 'mysql',
		dbName: 'shopware',
		expectedVersion: '6.6.',
		grepOmitFiltered: true,
		grepFilterSpecs: true,
	},
	// reporterOptions: {
	// 	env: {
	// 		apiPath: "**/api/*",
	// 			user: "admin",
	// 			pass: "shopware",
	// 			salesChannelName: "Storefront",
	// 			admin: "/admin",
	// 			locale: "de-CH",
	// 			projectRoot: "/app",
	// 			localUsage: false
	// 	}
	// }
})