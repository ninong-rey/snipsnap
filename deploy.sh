#!/bin/bash
# deploy.sh - Laravel deployment script

echo "🚀 Starting Laravel deployment..."

# 1. Install Composer dependencies (production)
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 2. Set permissions for storage & cache
echo "🔧 Setting permissions..."
chmod -R 775 storage bootstrap/cache

# 3. Run database migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "✅ No migrations needed or already up-to-date"

# 4. Create symbolic link for storage if it doesn't exist
if [ ! -L public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
else
    echo "🔗 Storage link already exists, skipping..."
fi

# 5. Generate fresh application key (force)
echo "🔑 Generating application key..."
php artisan key:generate --force

# 6. Clear and rebuild caches
echo "🧹 Clearing caches..."
php artisan optimize:clear
php artisan optimize

# 7. Optional: show composer PSR-4 warnings
echo "⚠️ Note: If you see PSR-4 warnings, make sure class filenames match namespaces."

echo "✅ Deployment script finished successfully!"
