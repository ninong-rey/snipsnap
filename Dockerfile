FROM php:8.2-apache

# Install system dependencies including PostgreSQL, git, and unzip
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache rewrite
RUN a2enmod rewrite

# Set document root to public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate application key
RUN php artisan key:generate --force

# ✅ CRITICAL: Create storage directories and link
RUN mkdir -p storage/app/public/videos
RUN mkdir -p storage/app/public/images
RUN php artisan storage:link

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache storage/app/public

# Run database migrations
RUN php artisan migrate --force

# Start Apache
CMD ["apache2-foreground"]