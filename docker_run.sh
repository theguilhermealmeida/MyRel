#!/bin/bash
set -e

cd /var/www; php artisan config
env >> /var/www/.env
php-fpm8.1 -D
nginx -g "daemon off;"
