import AccountPageObject from '../support/pages/account.page-object';
/**
 * @package checkout
 */
describe('Checkout: Full Process', () => {
  beforeEach(() => {
    cy.createCustomerFixtureStorefront()
      .then(() => {
        return cy.clearCookies();
      });
  });
  
  it('Add product to cart > Checkout', () => {
    const page = new AccountPageObject();
    cy.visit('/anschlagpuffer/1004.0');
    
    cy.get('.variant-row:not(.d-none) .btn-buy').first().should('be.visible');
    cy.get('.variant-row:not(.d-none) .btn-buy').first().click();
    cy.get('.cart-offcanvas.show').should('be.visible');

    cy.visit('/account/login');
    
    cy.get('#loginMail').typeAndCheckStorefront('test@example.com');
    cy.get('#loginPassword').typeAndCheckStorefront('shopware');
    cy.get(`${page.elements.loginSubmit} [type="submit"]`).click();
    cy.get('.account.account-content h1').should((element) => {
      expect(element).to.contain('Mein Konto');
    });
    
    cy.visit('/checkout/confirm');
    cy.get('#nextBtn').should('be.visible');
    cy.get('#nextBtn').click();
    
    // // Check Shipping options
    cy.get('.shipping-method-description').contains('Versand & Lieferung').should('be.visible')
    cy.get('.shipping-method-description').contains('Abholung ab Lager').should('be.visible')
    cy.get('.shipping-method-description').contains('Abholung ab Lager').click();
    cy.get('#nextBtn').should('be.visible');
    cy.get('#nextBtn').click();
    
    // Check Payment options
    cy.get('.payment-method-description').contains('Rechnung').should('be.visible')
    cy.get('#nextBtn').should('be.visible');
    cy.get('#nextBtn').click();
    
    // accept AGBs
    cy.get('.confirm-payment-method').contains('Rechnung').should('be.visible')
    cy.get('#tos').check();
    cy.get('#confirmFormSubmit').click();
    cy.get('.finish-header').contains('Vielen Dank für Ihre Bestellung bei Vibraplast AG!').should('be.visible')
  });
});
