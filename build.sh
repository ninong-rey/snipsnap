#!/bin/bash
set -e
composer install --no-dev --optimize-autoloader
php artisan migrate --force
