import 'cypress-file-upload';
import 'cypress-real-events/support';
import 'cypress-network-idle';
import 'cypress-real-events';

// Require test suite commands
require('@shopware-ag/e2e-testsuite-platform/cypress/support');
require('./commands/ww-commands');

// this sets the default browser locale to the environment variable
Cypress.on('window:before:load', (window) => {
	Object.defineProperty(window.navigator, 'language', {
		value: Cypress.env('locale'),
	});
});

beforeEach(() => {
	if (!Cypress.env('SKIP_AUTH')) {
		return cy.authenticate();
		
		// return cy.authenticate().then(() => {
			// if (!Cypress.env('SKIP_INIT')) {
			// 	return cy.setToInitialState().then(() => {
			// 		return cy.authenticate();
			// 	});
			// }
		// });
	}
});

// we need to use the classic function syntax to bind `this` correctly
afterEach(function () {
	const { state, _currentRetry, _retries } = this.currentTest;
	if (Cypress.env('INTERRUPT_ON_ERROR') && state === 'failed' && _currentRetry >= _retries) {
		throw new Error('Interrupt');
	}
});