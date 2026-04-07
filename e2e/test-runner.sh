#!/bin/bash
SW_EXEC="docker compose -f docker/docker-compose.yml exec e2e_dockware bash -c"
DB_EXEC="docker compose -f docker/docker-compose.yml exec e2e_db"

docker compose -f docker/docker-compose.yml up -d --force-recreate
$SW_EXEC "rm -rf config/packages/redis.yaml"

$SW_EXEC "nvm install 20"
$SW_EXEC "nvm use 20"
$SW_EXEC "sudo chown -R 33:33 '/var/www/.npm'"

$SW_EXEC "touch install.lock"
$SW_EXEC "sudo cp config/packages/dev/system_config.yaml config/packages/prod/system_config.yaml"

$DB_EXEC mysql -u root -proot -e "DROP DATABASE IF EXISTS shopware"
$DB_EXEC mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS shopware"
$DB_EXEC cp /var/sql/dump.sql.gz /var/sql/dump-source.sql.gz
$DB_EXEC gunzip /var/sql/dump-source.sql.gz
$DB_EXEC chmod 777 /var/sql/dump-source.sql
$DB_EXEC bash -c "mysql -u root -proot shopware < /var/sql/dump-source.sql"


$DB_EXEC mysql -u root -proot -e "USE shopware; UPDATE sales_channel_domain SET url = 'https://localhost' WHERE url = 'https://vibraplast.webwirkungdev.ch';"
$DB_EXEC mysql -u root -proot -e "USE shopware; UPDATE system_config SET configuration_value = '{\"_value\":{\"honeypot\":{\"name\":\"Honeypot\",\"isActive\":false},\"basicCaptcha\":{\"name\":\"basicCaptcha\",\"isActive\":false},\"googleReCaptchaV2\":{\"name\":\"googleReCaptchaV2\",\"isActive\":false,\"config\":{\"siteKey\":\"\",\"secretKey\":\"\",\"invisible\":false}},\"googleReCaptchaV3\":{\"name\":\"googleReCaptchaV3\",\"isActive\":false,\"config\":{\"siteKey\":\"\",\"secretKey\":\"\"}},\"friendlyCaptcha\":{\"name\":\"friendlyCaptcha\",\"isActive\":false,\"config\":{\"siteKey\":\"\",\"secretKey\":\"\"}}}}' WHERE configuration_key = 'core.basicInformation.activeCaptchasV2';"
$DB_EXEC mysql -u root -proot -e "USE shopware; DELETE FROM system_config WHERE configuration_key = 'core.mailerSettings.disableDelivery';"
$DB_EXEC mysql -u root -proot -e "USE shopware; INSERT INTO system_config (id, configuration_key, configuration_value, sales_channel_id, created_at) VALUES (UNHEX(REPLACE(UUID(), '-', '')), 'core.mailerSettings.disableDelivery', '{\"_value\":true}', NULL, NOW());"
$DB_EXEC mysql -u root -proot -e "USE shopware; DELETE FROM system_config WHERE configuration_key = 'WebwirkungTagmanagerPlugin.config.tagmanager';"
$DB_EXEC mysql -u root -proot -e "USE shopware; UPDATE sales_channel_analytics set tracking_id ='';"
$DB_EXEC mysql -u root -proot -e "USE shopware; DELETE FROM user;"

$SW_EXEC "composer install --no-scripts"

$SW_EXEC "php bin/console system:update:prepare"
$SW_EXEC "php bin/console system:update:finish"
$SW_EXEC "php -d memory_limit=-1 bin/console theme:refresh"
$SW_EXEC "./bin/build-js.sh"
$SW_EXEC "php bin/console theme:compile"
$SW_EXEC "php bin/console sales-channel:maintenance:disable --all"
$SW_EXEC "php bin/console cache:clear"
$SW_EXEC "php bin/console user:create -a admin -pshopware"
$SW_EXEC "sudo chmod 777 -R var/cache/ var/log/"

$SW_EXEC "sudo chmod 777 -R /var/www/html/e2e"
$SW_EXEC "cd /var/www/html/e2e && npm install"