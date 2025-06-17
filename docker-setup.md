# Docker Setup for Laravel Livewire Application

This setup provides a complete development environment with nginx, PHP-FPM, and Node.js for frontend compilation, optimized for Livewire applications.

## Prerequisites

- Docker Desktop installed
- Docker Compose installed

## Services Included

- **app**: PHP 8.2-FPM with Laravel and Livewire
- **nginx**: Web server configured for Laravel and Livewire
- **node**: Node.js 20 for Vite development server and hot reloading
- **redis**: Redis for caching and sessions (optional)

## Quick Start

1. **Copy environment file:**
   ```bash
   cp .env.docker .env
   ```

2. **Generate application key:**
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

3. **Install dependencies and build:**
   ```bash
   docker-compose up --build
   ```

4. **Run migrations (if using database):**
   ```bash
   docker-compose exec app php artisan migrate
   ```

## Access Points

- **Application**: http://localhost
- **Vite Dev Server**: http://localhost:5173 (for hot reloading)
- **Redis**: localhost:6379

## Development Workflow

### For Production Build
```bash
# Build assets for production
docker-compose exec node npm run build

# Restart nginx to serve new assets
docker-compose restart nginx
```

### For Development with Hot Reloading
The Node.js container automatically runs `npm run dev` with Vite's development server. Any changes to:
- Livewire components
- Blade templates
- CSS/JS files

Will trigger automatic browser reloads.

### Common Commands

```bash
# Start all services
docker-compose up

# Start in background
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Execute commands in containers
docker-compose exec app php artisan tinker
docker-compose exec app composer install
docker-compose exec node npm install

# Rebuild containers
docker-compose up --build

# Clean rebuild (removes volumes)
docker-compose down -v
docker-compose up --build
```

## File Structure

```
docker/
├── nginx/
│   ├── default.conf     # Nginx server configuration
│   └── nginx.conf       # Main nginx configuration
├── php/
│   └── local.ini        # PHP configuration
└── supervisord.conf     # Process supervisor config

.env.docker              # Docker environment template
.dockerignore           # Docker build ignore rules
Dockerfile              # Main application container
docker-compose.yml      # Service orchestration
```

## Livewire Optimization

The setup includes specific optimizations for Livewire:

1. **Nginx Configuration**:
   - Increased timeouts for long-running requests
   - Proper header forwarding
   - Optimized static file serving

2. **PHP Configuration**:
   - Increased memory limits
   - Optimized session handling
   - OpCache enabled for performance

3. **Vite Configuration**:
   - File watching with polling for Docker
   - Hot reloading for Livewire components
   - Proper host binding for containers

## Troubleshooting

### Port Conflicts
If ports 80, 5173, or 6379 are already in use, modify the port mappings in `docker-compose.yml`:

```yaml
ports:
  - "8080:80"  # Change from 80:80
```

### Permission Issues
```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Clear Caches
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Asset Compilation Issues
```bash
# Clear node modules and reinstall
docker-compose exec node rm -rf node_modules package-lock.json
docker-compose exec node npm install
```

## Production Considerations

For production deployment:

1. Change `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database connection
4. Use `npm run build` instead of `npm run dev`
5. Consider using a reverse proxy like Traefik or nginx-proxy
6. Enable SSL/TLS certificates
7. Configure proper logging and monitoring

## Security Notes

- The setup uses development configurations
- For production, review and harden all security settings
- Change default credentials and secrets
- Enable proper firewall rules
- Use secure communication channels