#!/bin/bash

echo "Standalone Laravel Deployment with External Services"
echo "=================================================="

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from template..."
    cp .env.docker .env
    echo "✅ .env file created. Please configure it with your external service details."
else
    echo "✅ .env file already exists"
fi

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

# Generate SSL certificates for development
echo "Generating SSL certificates for development..."
if [ ! -d "docker/nginx/ssl" ]; then
    mkdir -p docker/nginx/ssl
    ./docker/nginx/generate-ssl.sh
else
    echo "SSL certificates already exist"
fi

# Install PHP dependencies
echo "Installing PHP dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    echo "⚠️  Composer not found. Installing dependencies inside Docker container..."
    docker run --rm -v $(pwd):/app composer:latest install --no-dev --optimize-autoloader --no-interaction
fi

# Install Node.js dependencies and build assets
echo "Installing Node.js dependencies..."
if command -v npm &> /dev/null; then
    npm ci --only=production
    echo "Building frontend assets..."
    npm run build
else
    echo "⚠️  Node.js not found. Building assets inside Docker container..."
    docker run --rm -v $(pwd):/app -w /app node:18-alpine sh -c "npm ci --only=production && npm run build"
fi

# Build Docker image
echo "Building Docker image..."
docker-compose build

# Start the application
echo "Starting the application..."
docker-compose up -d

# Wait for container to be ready
echo "Waiting for application to start..."
sleep 10

# Check if container is running
if docker-compose ps | grep -q "Up"; then
    echo "✅ Application container is running"
    
    # Run Laravel setup commands
    echo "Running Laravel setup commands..."
    docker-compose exec -T app php artisan key:generate --force || echo "Key already generated"
    docker-compose exec -T app php artisan config:cache
    docker-compose exec -T app php artisan route:cache
    docker-compose exec -T app php artisan view:cache
    
    # Test database connection and run migrations
    echo "Testing database connection..."
    if docker-compose exec -T app php artisan migrate:status; then
        echo "✅ Database connection successful"
        echo "Running database migrations..."
        docker-compose exec -T app php artisan migrate --force
    else
        echo "⚠️  Database connection failed. Please check your external database configuration."
    fi
    
    # Create storage link
    echo "Creating storage link..."
    docker-compose exec -T app php artisan storage:link || echo "Storage link already exists"
    
    echo ""
    echo "🎉 Deployment completed successfully!"
    echo ""
    echo "Application Details:"
    echo "- Local URL: http://localhost:3000"
    echo "- Production URL: https://kreasi-kita.my.id (via Cloudflare Tunnel)"
    echo "- Container Status: docker-compose ps"
    echo "- Logs: docker-compose logs -f"
    echo ""
    echo "Next Steps:"
    echo "1. Configure your external database and Redis connections in .env"
    echo "2. Set up Cloudflare Tunnel to point to http://localhost:3000"
    echo "3. Test your application"
    echo ""
    echo "Cloudflare Tunnel Commands:"
    echo "cloudflared tunnel create kreasi-kita-tunnel"
    echo "cloudflared tunnel route dns kreasi-kita-tunnel kreasi-kita.my.id"
    echo "cloudflared tunnel route dns kreasi-kita-tunnel ws.kreasi-kita.my.id"
    echo "cloudflared tunnel --config cloudflare-tunnel.yml run"
    
else
    echo "❌ Application failed to start. Check logs with: docker-compose logs"
    exit 1
fi