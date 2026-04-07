import AccountPageObject from '../support/pages/account.page-object';
/**
 * @package checkout
 */
describe('Account: Login as customer', () => {
  beforeEach(() => {
    cy.createCustomerFixtureStorefront()
      .then(() => {
        return cy.clearCookies();
      });
  });
  
  it('Login with wrong credentials', () => {
    const page = new AccountPageObject();
    cy.visit('/account/login');
    
    cy.get(page.elements.loginCard).should('be.visible');
    cy.get('#loginMail').typeAndCheckStorefront('test@example.com');
    cy.get('#loginPassword').typeAndCheckStorefront('Anything');
    cy.get(`${page.elements.loginSubmit} [type="submit"]`).click();
    
    cy.get('.alert-danger').should((element) => {
      expect(element).to.contain('Es konnte kein Account mit den angegebenen Zugangsdaten gefunden werden.');
    });
  });
  
  it('Login with valid credentials', () => {
    const page = new AccountPageObject();
    cy.visit('/account/login');
    
    cy.get('#loginMail').typeAndCheckStorefront('test@example.com');
    cy.get('#loginPassword').typeAndCheckStorefront('shopware');
    cy.get(`${page.elements.loginSubmit} [type="submit"]`).click();
    cy.get('.account.account-content h1').should((element) => {
      expect(element).to.contain('Mein Konto');
    });
  });
});
