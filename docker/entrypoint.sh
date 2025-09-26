#!/bin/bash

# Set proper permissions for Laravel application
echo "Setting up permissions..."

# Change ownership to www user
chown -R www:www /var/www/html

# Set general permissions
chmod -R 755 /var/www/html

# Set writable permissions for Laravel directories
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/public/build

# Ensure build directory exists and has proper structure
mkdir -p /var/www/html/public/build/assets

# Copy build assets if they exist in the container but not in host
if [ -f /var/www/html/public/build/manifest.json ] && [ ! -f /var/www/html/public/build/manifest.json ]; then
    echo "Copying build assets to host..."
    cp -r /var/www/html/public/build/* /var/www/html/public/build/ 2>/dev/null || true
fi

# Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key..."
    php artisan key:generate --ansi
fi

# Run npm build if build assets don't exist or are outdated
if [ ! -f /var/www/html/public/build/manifest.json ] || [ ! -d /var/www/html/public/build/assets ]; then
    echo "Building assets with npm..."
    npm run build
fi

echo "Starting PHP-FPM..."
exec php-fpm
