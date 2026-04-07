#!/bin/bash
./test-runner.sh

#Opening Cypress browser happens in local system, not in Docker
#that means that node needs to be installed on the local system
#It's not a good way to do that but so far I didn't find a way to run
#Cypress in Docker container and expose it in way that browser will be opened on the local system
npm i
npm run cy:open