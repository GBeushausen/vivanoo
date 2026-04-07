#!/bin/bash
./test-runner.sh
SW_EXEC="docker compose -f docker/docker-compose.yml exec e2e_dockware bash -c"
$SW_EXEC "cd /var/www/html/e2e && npm run cy:run"