# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install system dependencies and Composer
RUN apt-get update && apt-get install -y unzip git curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy environment example (safe fallback)
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Change Apache DocumentRoot to Laravel's public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# ✅ Start Laravel + Apache
CMD php artisan key:generate --force && apache2-foreground
