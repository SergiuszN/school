#!/bin/bash

git pull
composer install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
php bin/console doctrine:schema:update --dump-sql --force
php bin/console ckeditor:install --tag=4.22.1 --clear=drop
php bin/console assets:install public