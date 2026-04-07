# Vibraplast Shopware 6 Shop
This is the Shopware 6 project of Vibraplast. 

## Installation and usage instructions
Clone the Github repository to your local environment. 
### Requirements
- PHP >= 8.2 and memory_limit >= 512 MB
- Symfony CLI
- Docker

Copy .env.local.example to .env.local
```shell
cp .env.local.example .env.local
```
Get the token for ${TOKEN_SHOPWARE} from Buddy and replace it in the command below. This will create the needed auth.json file.  
Run the following command:
```shell
composer config bearer.packages.shopware.com ${TOKEN_SHOPWARE}
```
Generate the SHOP_ID with the following command (add it to your .env.local):
```shell
openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 16; echo
```
Install the packages:
```shell
composer install
```
Start the database:
```shell
docker-compose up -d
```
Basic Setup (or import database)
```shell
symfony console system:install --basic-setup
```
Generate JWT Token
```shell
symfony console system:generate-jwt-secret --use-env
```
and add JWT_PRIVATE_KEY and JWT_PUBLIC_KEY to the .env.local file. 

and run the local webserver:
```shell
symfony server:start -d
```

## Import database from staging or production
If you have a database dump from staging or production, please run:
```shell
docker exec -i vibraplast-shopware-database-1 bash -c "mysql -ushopware -pshopware -e \"DROP DATABASE IF EXISTS shopware;\""
docker exec -i vibraplast-shopware-database-1 bash -c "mysql -ushopware -pshopware -e \"create database shopware CHARACTER SET utf8 COLLATE utf8_general_ci;\""
docker exec -i vibraplast-shopware-database-1 mysql -ushopware -pshopware shopware < ./dump.sql
```
then create an admin user:
```shell
bin/console user:create -a -p shopware admin
```
and disable the maintenance mode for all sales-channels:
```shell
bin/console sales-channel:maintenance:disable --all
```
Unfortunately you will need to edit the sales-channel domains by yourself to http://127.0.0.1:8000.

## Dump your local database
```shell
docker exec -i vibraplast-shopware-database-1 bash -c "mysqldump -u shopware -pshopware shopware" > dump_local.sql
```

## Work locally
```shell
bin/watch-storefront.sh
bin/watch-administration.sh

bin/build-js.sh
```

## Disable services like Redis cache or OpenSearch
By default, a Redis cache and OpenSearch are enabled. If you want to disable them, please follow these steps:
### Disable Redis cache
1. Remove the redis.yaml file in config/packages/
2. Add donotstart profile to docker-compose.override.yaml
```yaml
      redis:
        image: redis:7.2.1
        ports:
          - "6379:6379"

        profiles:
            - donotstart
```

### Disable OpenSearch
Remove the two environment variables in your .env.local file:
```dotenv
SHOPWARE_ES_ENABLED=1
SHOPWARE_ES_INDEXING_ENABLED=1
```
and add donotstart profile to docker-compose.override.yaml
```yaml
      elasticsearch:
        image: opensearchproject/opensearch:2.7.0
        ports:
          - "9200:9200"
        profiles:
            - donotstart
```

## Check translations in DB and files (PHP Unuhi)

PHP Unuhi is configured in two files:
- `phpunuhi.xml` - Checks the translations in the **files** for snippets
- `phpunuhi_database.xml` - Checks the translations in the **database** for products, categories, properties and snippets

Find some useful commands below:
```shell
# Check all translations for missing keys, structures, and content in files
php vendor/bin/phpunuhi validate

# Check translations in the database for products, categories, properties and snippets
php vendor/bin/phpunuhi validate --configuration=./phpunuhi_database.xml 

# Check the coverage of your translations
php vendor/bin/phpunuhi validate:coverage
php vendor/bin/phpunuhi validate:coverage --configuration=./phpunuhi_database.xml

# Scan through your files in custom folder for snippets that are not used in twig
php vendor/bin/phpunuhi scan:usage --dir=./custom --scanner=twig

# If you have missing translations you can automatically add empty translation keys by
php vendor/bin/phpunuhi fix:structure
```

## Staging Mode
Staging mode is available the configuration can be done in `config/packages/staging.yaml` and can be set by:
```shell
bin/console system:setup:staging
```
