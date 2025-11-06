FROM php:8.2-apache

# Install system dependencies including git, unzip, and PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear and cache routes
RUN php artisan config:clear
RUN php artisan route:clear  
RUN php artisan route:cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Run migrations and start PHP server
CMD sh -c "php artisan migrate --force && php -S 0.0.0.0:80 -t public"