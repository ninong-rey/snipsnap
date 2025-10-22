# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Enable necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install system dependencies and Composer
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ CRITICAL FIX: Set Apache DocumentRoot to public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ✅ FIX: Create .env file if it doesn't exist and generate key
RUN if [ ! -f .env ]; then \
        cp .env.example .env && \
        php artisan key:generate --force; \
    else \
        php artisan key:generate --force; \
    fi

# ✅ FIX: Create SQLite database file
RUN touch /var/www/html/database/database.sqlite
RUN chmod 775 /var/www/html/database/database.sqlite

# ✅ CRITICAL FIX: Nuclear option for config cache
RUN rm -rf bootstrap/cache/*
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan optimize:clear

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
