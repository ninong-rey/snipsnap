FROM php:8.2-apache

# Install system dependencies including PostgreSQL, git, and unzip
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Increase PHP limits for LARGE video uploads (5GB)
RUN echo "upload_max_filesize = 5G" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = 5G" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_input_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini

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

# Run database migrations
RUN php artisan migrate --force

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Start Apache
CMD ["apache2-foreground"]
