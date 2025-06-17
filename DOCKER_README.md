# Docker Setup for Laravel with Livewire and Cloudflare Tunnel

This Docker setup provides a complete development and production environment for a Laravel application with Livewire, including nginx, php-fpm, MySQL, Redis, and Node.js for frontend compilation.

## Features

- **Multi-stage Docker build** with optimized frontend compilation
- **Nginx** configured for Laravel and Livewire with WebSocket support
- **PHP-FPM 8.2** with optimized settings for Laravel
- **MySQL 8.0** database
- **Redis** for caching and session storage
- **Node.js 18** for frontend asset compilation
- **Queue worker** for background job processing
- **WebSocket server** for real-time features
- **Cloudflare Tunnel** ready configuration
- **SSL support** with self-signed certificates for development

## Services

### Application Stack
- **app**: Main Laravel application (PHP-FPM)
- **nginx**: Web server and reverse proxy
- **db**: MySQL 8.0 database
- **redis**: Redis cache and session store
- **node**: Node.js for frontend development
- **queue**: Laravel queue worker
- **websockets**: WebSocket server for real-time features

### Ports
- **80**: HTTP web server
- **443**: HTTPS web server
- **3306**: MySQL database
- **6379**: Redis
- **5173**: Vite development server
- **6001**: WebSocket server

## Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Git (for cloning the repository)

### Setup

1. **Clone and navigate to your project**:
   ```bash
   cd /path/to/your/laravel/project
   ```

2. **Run the setup script**:
   ```bash
   chmod +x docker-setup.sh
   ./docker-setup.sh
   ```

3. **Configure your .env file** with the Docker settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=laravel_password

   REDIS_HOST=redis
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   BROADCAST_DRIVER=pusher
   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis
   ```

4. **Access your application**:
   - HTTP: http://localhost
   - HTTPS: https://localhost (self-signed certificate)

## Manual Setup

If you prefer to set up manually:

1. **Generate SSL certificates**:
   ```bash
   ./docker/nginx/generate-ssl.sh
   ```

2. **Build and start containers**:
   ```bash
   docker-compose build
   docker-compose up -d
   ```

3. **Install dependencies and set up Laravel**:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan storage:link
   ```

4. **Install and build frontend assets**:
   ```bash
   docker-compose exec node npm install
   docker-compose exec node npm run build
   ```

## Development Workflow

### Frontend Development
For live reloading during development:
```bash
docker-compose exec node npm run dev
```
Access via: http://localhost:5173

### Running Artisan Commands
```bash
docker-compose exec app php artisan [command]
```

### Database Operations
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Seed database
docker-compose exec app php artisan db:seed

# Access MySQL directly
docker-compose exec db mysql -u laravel -p laravel
```

### Queue Management
```bash
# Monitor queue
docker-compose logs -f queue

# Restart queue worker
docker-compose restart queue
```

## Livewire Configuration

This setup is optimized for Livewire with:

1. **Proper nginx configuration** for Livewire routes
2. **WebSocket support** for real-time features
3. **Redis session storage** for better performance
4. **Optimized PHP settings** for Livewire components

### WebSocket Support
If using Laravel WebSockets package:

1. Install the package:
   ```bash
   docker-compose exec app composer require beyondcode/laravel-websockets
   ```

2. Configure broadcasting in your `.env`:
   ```env
   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=local
   PUSHER_APP_KEY=local
   PUSHER_APP_SECRET=local
   PUSHER_HOST=localhost
   PUSHER_PORT=6001
   PUSHER_SCHEME=http
   ```

## Cloudflare Tunnel Setup

### Prerequisites
1. Cloudflare account with a domain
2. `cloudflared` installed locally

### Setup Steps

1. **Create a tunnel**:
   ```bash
   cloudflared tunnel create your-tunnel-name
   ```

2. **Update the tunnel configuration**:
   - Edit `cloudflare-tunnel.yml`
   - Replace `your-tunnel-name` with your actual tunnel name
   - Replace `your-domain.com` with your actual domain

3. **Configure DNS**:
   ```bash
   cloudflared tunnel route dns your-tunnel-name your-domain.com
   cloudflared tunnel route dns your-tunnel-name ws.your-domain.com
   ```

4. **Run the tunnel**:
   ```bash
   cloudflared tunnel --config cloudflare-tunnel.yml run
   ```

### Production Deployment

For production deployment with Cloudflare Tunnel:

1. **Update docker-compose.yml** for production:
   ```yaml
   services:
     app:
       environment:
         - APP_ENV=production
         - APP_DEBUG=false
   ```

2. **Use a proper SSL certificate** or let Cloudflare handle SSL

3. **Configure Cloudflare settings**:
   - Enable "Websockets" in Cloudflare dashboard
   - Set appropriate security settings
   - Configure caching rules

## Troubleshooting

### Common Issues

1. **Permission issues**:
   ```bash
   docker-compose exec app chown -R www-data:www-data /var/www/html/storage
   docker-compose exec app chmod -R 755 /var/www/html/storage
   ```

2. **Database connection issues**:
   - Ensure database service is running: `docker-compose ps`
   - Check database logs: `docker-compose logs db`

3. **Frontend assets not loading**:
   ```bash
   docker-compose exec node npm run build
   docker-compose restart nginx
   ```

4. **Livewire not working**:
   - Check nginx configuration for `/livewire` routes
   - Verify WebSocket configuration
   - Check browser console for errors

### Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f [service-name]

# Nginx access logs
docker-compose exec nginx tail -f /var/log/nginx/access.log
```

## File Structure

```
├── Dockerfile                 # Multi-stage build configuration
├── docker-compose.yml         # Docker services configuration
├── .dockerignore              # Files to ignore during build
├── docker-setup.sh            # Quick setup script
├── cloudflare-tunnel.yml      # Cloudflare tunnel configuration
├── docker/
│   ├── nginx/
│   │   ├── default.conf       # Nginx server configuration
│   │   ├── generate-ssl.sh    # SSL certificate generator
│   │   └── ssl/               # SSL certificates directory
│   ├── php/
│   │   ├── php-fpm.conf       # PHP-FPM pool configuration
│   │   └── local.ini          # PHP configuration
│   └── supervisord.conf       # Process supervisor configuration
└── DOCKER_README.md          # This file
```

## Security Considerations

1. **Change default passwords** in production
2. **Use proper SSL certificates** for production
3. **Configure Cloudflare security settings**
4. **Limit database access** to application containers only
5. **Regular security updates** for base images

## Performance Optimization

1. **OPcache enabled** for PHP performance
2. **Redis caching** for sessions and application cache
3. **Nginx gzip compression** enabled
4. **Static asset caching** configured
5. **Optimized PHP-FPM settings** for concurrent requests

## Support

For issues related to:
- **Docker setup**: Check Docker and Docker Compose documentation
- **Laravel/Livewire**: Check Laravel and Livewire documentation
- **Cloudflare Tunnel**: Check Cloudflare documentation