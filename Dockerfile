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

# ✅ CRITICAL: Remove migration from build phase - run it at startup instead
# RUN php artisan migrate --force  # REMOVE THIS LINE

# ✅ NUCLEAR FIX: Force file sessions in config file
RUN sed -i "s/'driver' => env('SESSION_DRIVER', 'database'),/'driver' => 'file',/g" config/session.php
RUN echo "SESSION_DRIVER=file" >> .env

# ✅ Safe cache clearing (NO commands that require database)
RUN rm -rf bootstrap/cache/*
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# Expose port 80
EXPOSE 80

# Create a startup script that runs migrations and starts Apache
RUN echo '#!/bin/bash\n\
php artisan migrate --force\n\
apache2-foreground' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Use the startup script
CMD ["/usr/local/bin/start.sh"]
FROM php:8.2-apache

# Install system dependencies and PostgreSQL support
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Apache DocumentRoot to public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Clear cache
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# Run database migrations  ← ADD THIS LINE
RUN php artisan migrate --force

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
FROM php:8.2-apache

# Install system dependencies and PostgreSQL support
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set Apache DocumentRoot to public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Clear cache
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
