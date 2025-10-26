FROM php:8.2-apache

# Install PostgreSQL support
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

# Enable mod_rewrite
RUN a2enmod rewrite

EXPOSE 80

# Run migrations with error handling
CMD sh -c "php artisan config:clear && php artisan migrate --force || true && apache2-foreground"
