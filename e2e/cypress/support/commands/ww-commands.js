const CmsFixture = require('@shopware-ag/e2e-testsuite-platform/cypress/support/service/administration/fixture/cms.fixture');
const RuleBuilderFixture = require('@shopware-ag/e2e-testsuite-platform/cypress/support/service/fixture/rule-builder.fixture');


Cypress.Commands.add("getByData", (selector) => {
	return cy.get(`[data-cypress=${selector}]`)
})

Cypress.Commands.add("selectNth", (select, pos) => {
	cy.get(`${select}`)
		.eq(pos)
})

/**
 * Create cms fixture using Shopware API at the given endpoint
 * @memberOf Cypress.Chainable#
 * @name createCmsFixture
 * @function
 * @param {Object} [userData={}] - Options concerning creation
 */
Cypress.Commands.add('createProductCmsFixture', (userData = {}) => {
	return cy.getBearerAuth().then((authInformation) => {
		const fixture = new CmsFixture(authInformation);
		let pageJson = null;
		
		return cy.fixture('cms-product-page').then((data) => {
			pageJson = data;
			return cy.fixture('cms-section')
		}).then((data) => {
			return Cypress._.merge(pageJson, {
				sections: [data]
			});
		}).then((data) => {
			return Cypress._.merge(pageJson, userData);
		}).then((data) => {
			return fixture.setCmsPageFixture(data);
		});
	});
});

/**
 * Set a rule using Shopware API at the given endpoint
 * @memberOf Cypress.Chainable#
 * @name createRuleFixture
 * @function
 * @param {Object} userData - Custom data for the request
 * @param {String} [shippingMethodName=Standard] - Name of the shipping method
 */
Cypress.Commands.add('createRuleFixtureWw', (userData, shippingMethodName = 'Standard', fileName) => {
	cy.authenticate().then((authInformation) => {
		const fixture = new RuleBuilderFixture(authInformation);
		return cy.fixture(fileName).then((result) => {
			return fixture.setRuleFixture(Cypress._.merge(result, userData), shippingMethodName);
		});
	});
});