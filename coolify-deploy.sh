#!/bin/bash

echo "Coolify Deployment Script for Laravel with Livewire"
echo "=================================================="

# Create necessary directories
echo "Creating necessary directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file from .env.docker template..."
    cp .env.docker .env
    echo ""
    echo "⚠️  Please configure your .env file with Coolify environment variables:"
    echo ""
    echo "Database (provided by Coolify):"
    echo "DB_CONNECTION=mysql"
    echo "DB_HOST=\${DB_HOST}"
    echo "DB_PORT=\${DB_PORT}"
    echo "DB_DATABASE=\${DB_DATABASE}"
    echo "DB_USERNAME=\${DB_USERNAME}"
    echo "DB_PASSWORD=\${DB_PASSWORD}"
    echo ""
    echo "Redis (provided by Coolify):"
    echo "REDIS_HOST=\${REDIS_HOST}"
    echo "REDIS_PASSWORD=\${REDIS_PASSWORD}"
    echo "REDIS_PORT=\${REDIS_PORT}"
    echo ""
    echo "Application settings:"
    echo "APP_URL=https://your-domain.com"
    echo "CACHE_DRIVER=redis"
    echo "SESSION_DRIVER=redis"
    echo "QUEUE_CONNECTION=redis"
    echo ""
fi

# Install PHP dependencies
echo "Installing PHP dependencies..."
if [ -f "composer.lock" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Install Node.js dependencies and build assets
echo "Installing Node.js dependencies..."
npm ci --only=production

echo "Building frontend assets..."
npm run build

# Generate application key if not set
echo "Checking application key..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Run Laravel optimizations
echo "Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (if database is available)
echo "Running database migrations..."
php artisan migrate --force || echo "⚠️  Database not available or migration failed"

# Create storage link
echo "Creating storage link..."
php artisan storage:link || echo "Storage link already exists"

echo ""
echo "✅ Coolify deployment preparation complete!"
echo ""
echo "Next steps for Coolify:"
echo "1. Create a new application in Coolify"
echo "2. Connect your Git repository"
echo "3. Set up external services (MySQL, Redis)"
echo "4. Configure environment variables in Coolify dashboard"
echo "5. Deploy your application"
echo ""
echo "Required environment variables for Coolify:"
echo "- APP_KEY (auto-generated)"
echo "- DB_* variables (from Coolify database service)"
echo "- REDIS_* variables (from Coolify redis service)"
echo "- APP_URL (your domain)"
echo ""
echo "Optional for Cloudflare Tunnel:"
echo "- Configure tunnel in Cloudflare dashboard"
echo "- Point tunnel to your Coolify application URL"
echo "- Enable WebSocket support in Cloudflare"