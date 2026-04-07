## Cypress Tests
Our starter comes with Cypress for e2e testing the project. 

In addition to Cypress we use the `@shopware-ag/e2e-testsuite-platform` which provides us with some helper functions, fixtures etc.  
But we use Cypress version `13.6.6` in this version the structure is a bit different. Anything else seems to work fine in combination with `@shopware-ag/e2e-testsuite-platform`

-------------------
#### Table of contents:
- [Default tests](#default-tests)
- [Local testing](#local-testing)
- [Add new tests](#add-new-tests)
- [Buddy pipeline](#buddy-pipeline)
-------------------

⚠️ Before you start with running the tests, please make sure to update the tests in `cypress/e2e` and the fixtures in `cypress/fixtures` to match your project. Have a look at the added Todos in the tests.

## Default tests
Find some default cypress tests already in the folder `e2e/cypress/e2e` which test the following scenarios:
- Login

### Update fixtures and tests before running tests
For the default tests to run properly update 
- the fixture json files and update all ids. (E.g. country and salutation id in the `e2e/cypress/fixtures/customer-address.json` file)
- Change the product to be added in 3_checkout_process.cy.js

## Local testing
- Make sure that port 443 is free on your local machine. Disable any service or Docker container that uses this port.
- Place database dump from Buddy pipeline in `e2e/docker/db/database` directory. Name it as `dump.sql.gz`
- Go to `e2e` directory and run `./run-tests.sh` script to run all tests in headless mode
- (NODE required) Go to `e2e` directory and run `./open-tests.sh` script to run Cypress in browser mode 

### Run tests locally
Enter this folder with your terminal and run the following commands:

```shell
cd e2e
npm install
npm run cy:open
```

Run Cypress tests directly in the terminal: 

```
npm run cy:run
```

1. This will open the Cypress Test Runner, currently we only have E2E tests so choose E2E Testing in the screen.
2. Then you can choose a browser e.g. Chrome and click on the button below.

Now you see a list of all available tests from the folder
`e2e/cypress/e2e`

By clicking on them you can run the tests.

## Create new tests
To add new tests create a new file in the folder `e2e/cypress/e2e` and add your tests there.

### Custom command cy.getByData()
We have a custom command to select elements by data-cypress attribute. Add this to fields you want to select to be sure
it still works when you change the class or id of the element while working on the project.

`e2e/support/commands/ww-commands.js`

Attribute you can add to your element you want to check: 
```
data-cypress="exampleAttrValue"
```

Example use in cypress test:
```
cy.getByData('exampleAttrValue').click()
```

Examples Shopware: https://github.com/shopware/shopware/tree/trunk/tests/e2e
E2E Test Suite Platform: https://github.com/shopware/e2e-testsuite-platform

### Buddy pipeline
⚠️ ADD SHORT DESCRIPTION ON DEFAULT BUDDY PIPELINE HERE
