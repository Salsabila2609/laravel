#!/bin/sh

set -e  
composer install --no-dev --prefer-dist --optimize-autoloader
composer require ezyang/htmlpurifier:^4.18 --no-interaction --ansi
chmod -R 777 /var/www/vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer
php artisan storage:link
npm install
npm install dompurify
npm run build
exec "$@"
