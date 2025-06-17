#!/bin/bash

echo "Setting up Docker environment for Laravel with Livewire..."

# Make scripts executable
chmod +x docker/nginx/generate-ssl.sh

# Generate SSL certificates
echo "Generating SSL certificates..."
./docker/nginx/generate-ssl.sh

# Create necessary directories
echo "Creating necessary directories..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    echo "Please configure your .env file with the following Docker settings:"
    echo ""
    echo "DB_CONNECTION=mysql"
    echo "DB_HOST=db"
    echo "DB_PORT=3306"
    echo "DB_DATABASE=laravel"
    echo "DB_USERNAME=laravel"
    echo "DB_PASSWORD=laravel_password"
    echo ""
    echo "REDIS_HOST=redis"
    echo "REDIS_PASSWORD=null"
    echo "REDIS_PORT=6379"
    echo ""
    echo "BROADCAST_DRIVER=pusher"
    echo "CACHE_DRIVER=redis"
    echo "QUEUE_CONNECTION=redis"
    echo "SESSION_DRIVER=redis"
    echo ""
fi

# Build and start containers
echo "Building Docker containers..."
docker-compose build

echo "Starting Docker containers..."
docker-compose up -d

# Wait for database to be ready
echo "Waiting for database to be ready..."
sleep 30

# Run Laravel setup commands
echo "Running Laravel setup commands..."
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan storage:link

# Install and build frontend assets
echo "Installing and building frontend assets..."
docker-compose exec node npm install
docker-compose exec node npm run build

echo ""
echo "Setup complete! Your application should be available at:"
echo "HTTP: http://localhost"
echo "HTTPS: https://localhost (with self-signed certificate)"
echo ""
echo "Services:"
echo "- Web server: http://localhost"
echo "- Database: localhost:3306"
echo "- Redis: localhost:6379"
echo "- Vite dev server: http://localhost:5173"
echo "- WebSockets: ws://localhost:6001"
echo ""
echo "To view logs: docker-compose logs -f"
echo "To stop: docker-compose down"
echo "To rebuild: docker-compose down && docker-compose build && docker-compose up -d"