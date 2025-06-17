#!/bin/bash

echo "Docker Setup Validation Script"
echo "=============================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed or not in PATH"
    echo "Please install Docker Desktop from https://www.docker.com/products/docker-desktop"
    exit 1
else
    echo "✅ Docker is installed"
    docker --version
fi

# Check if Docker Compose is available
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose is not available"
    echo "Please ensure Docker Compose is installed"
    exit 1
else
    echo "✅ Docker Compose is available"
    if command -v docker-compose &> /dev/null; then
        docker-compose --version
    else
        docker compose version
    fi
fi

# Check if Docker daemon is running
if ! docker info &> /dev/null; then
    echo "❌ Docker daemon is not running"
    echo "Please start Docker Desktop"
    exit 1
else
    echo "✅ Docker daemon is running"
fi

# Validate docker-compose.yml syntax
if [ -f "docker-compose.yml" ]; then
    echo "✅ docker-compose.yml found"
    if docker-compose config &> /dev/null || docker compose config &> /dev/null; then
        echo "✅ docker-compose.yml syntax is valid"
    else
        echo "❌ docker-compose.yml has syntax errors"
        exit 1
    fi
else
    echo "❌ docker-compose.yml not found"
    exit 1
fi

# Check if Dockerfile exists
if [ -f "Dockerfile" ]; then
    echo "✅ Dockerfile found"
else
    echo "❌ Dockerfile not found"
    exit 1
fi

# Check required directories
required_dirs=("docker/nginx" "docker/php")
for dir in "${required_dirs[@]}"; do
    if [ -d "$dir" ]; then
        echo "✅ Directory $dir exists"
    else
        echo "❌ Directory $dir not found"
        exit 1
    fi
done

# Check required files
required_files=(
    "docker/nginx/default.conf"
    "docker/php/php-fpm.conf"
    "docker/php/local.ini"
    "docker/supervisord.conf"
)

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ File $file exists"
    else
        echo "❌ File $file not found"
        exit 1
    fi
done

# Check package.json for Node.js dependencies
if [ -f "package.json" ]; then
    echo "✅ package.json found"
    if grep -q '"vite"' package.json; then
        echo "✅ Vite found in package.json"
    else
        echo "⚠️  Vite not found in package.json"
    fi
else
    echo "❌ package.json not found"
    exit 1
fi

# Check composer.json for PHP dependencies
if [ -f "composer.json" ]; then
    echo "✅ composer.json found"
    if grep -q '"livewire/livewire"' composer.json; then
        echo "✅ Livewire found in composer.json"
    else
        echo "⚠️  Livewire not found in composer.json"
    fi
else
    echo "❌ composer.json not found"
    exit 1
fi

echo ""
echo "🎉 All validations passed!"
echo ""
echo "Next steps:"
echo "1. Run './docker-setup.sh' to set up the environment"
echo "2. Or manually run 'docker-compose up -d' to start services"
echo "3. Configure your .env file with Docker settings"
echo "4. Access your application at http://localhost"
echo ""
echo "For Cloudflare Tunnel:"
echo "1. Install cloudflared"
echo "2. Configure cloudflare-tunnel.yml with your settings"
echo "3. Run 'cloudflared tunnel --config cloudflare-tunnel.yml run'"