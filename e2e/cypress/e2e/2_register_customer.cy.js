import AccountPageObject from '../support/pages/account.page-object';
/**
 * @package checkout
 */
describe('Account: Register new customer', () => {
  beforeEach(() => {
    cy.createCustomerFixtureStorefront()
      .then(() => {
        return cy.clearCookies();
      });
  });
  
  it('Register new customer > Fail', () => {
    const page = new AccountPageObject();
    cy.visit('/account/login');
    cy.get('.col-lg-8 > .card > .card-body > h2.card-title').contains('Ich bin Neukunde').should('be.visible');
    cy.get('#personalFirstName').typeAndCheckStorefront('John');
    cy.get('#personalLastName').typeAndCheckStorefront('Doe');
    cy.get('.register-submit > .btn').click();
    cy.get('.register-form.was-validated').should('be.visible')
  });
  
  it('Register new customer > Success', () => {
    const page = new AccountPageObject();
    cy.visit('/account/login');
    cy.get('.col-lg-8 > .card > .card-body > h2.card-title').contains('Ich bin Neukunde').should('be.visible');
    cy.get('#accountType').select('Privat');
    cy.get('#personalFirstName').typeAndCheckStorefront('John');
    cy.get('#personalLastName').typeAndCheckStorefront('Doe');
    cy.get('#personalMail').typeAndCheckStorefront('john.doe@email.com');
    cy.get('#personalPassword').typeAndCheckStorefront('shopware1234');
    cy.get('#billingAddressAddressCountry').select('Schweiz');
    cy.get('#personalPasswordConfirmation').typeAndCheckStorefront('shopware1234');
    cy.get('#billingAddressAddressStreet').typeAndCheckStorefront('Teststrasse 11');
    cy.get('#billingAddressAddressZipcode').typeAndCheckStorefront('1111');
    cy.get('#billingAddressAddressCity').typeAndCheckStorefront('Test Ort');
    cy.get('.register-submit > .btn').click();
    cy.get('.account.account-content h1').should((element) => {
      expect(element).to.contain('Mein Konto');
    });
  });
});
