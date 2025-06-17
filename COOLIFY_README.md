# Coolify Deployment Guide for Laravel with Livewire

This guide explains how to deploy your Laravel application with Livewire to Coolify, with support for Cloudflare Tunnel.

## Overview

The Docker setup has been optimized for Coolify deployment with:
- Single container with nginx + PHP-FPM + Laravel
- External database and Redis services managed by Coolify
- Built-in queue worker and scheduler
- Optimized for Livewire real-time features
- Cloudflare Tunnel compatibility

## Prerequisites

1. **Coolify instance** running and accessible
2. **Git repository** with your Laravel application
3. **Domain name** (optional, for Cloudflare Tunnel)

## Deployment Steps

### 1. Prepare Your Application

Run the preparation script:
```bash
./coolify-deploy.sh
```

This will:
- Create necessary directories
- Set proper permissions
- Install dependencies
- Build frontend assets
- Configure Laravel optimizations

### 2. Set Up Coolify Project

1. **Create New Project** in Coolify dashboard
2. **Add Application** from Git repository
3. **Configure Build Settings**:
   - Build command: `./coolify-deploy.sh`
   - Start command: (leave empty, uses Dockerfile CMD)
   - Port: `3000`

### 3. Configure External Services

#### Database (MySQL)
1. Create a **MySQL service** in Coolify
2. Note the connection details provided by Coolify

#### Redis (Optional but Recommended)
1. Create a **Redis service** in Coolify
2. Note the connection details provided by Coolify

### 4. Environment Variables

Set these environment variables in Coolify dashboard:

#### Required Variables
```env
APP_NAME="Your Laravel App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kreasi-kita.my.id

# Database (from Coolify MySQL service)
DB_CONNECTION=mysql
DB_HOST=mysql-service-host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cache & Sessions (recommended with Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis (from Coolify Redis service)
REDIS_HOST=redis-service-host
REDIS_PORT=6379
REDIS_PASSWORD=redis_password
```

#### Optional Variables
```env
# Mail (if using mail features)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password

# Social Login (if enabled)
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret

# Payment Gateway (if using Midtrans)
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_IS_PRODUCTION=false
```

### 5. Deploy

1. **Commit and push** your changes to Git
2. **Deploy** in Coolify dashboard
3. **Monitor logs** during deployment
4. **Test application** once deployed

## Livewire Configuration

### Real-time Features
If using Livewire with real-time features:

1. **Install Laravel WebSockets** (optional):
   ```bash
   composer require beyondcode/laravel-websockets
   ```

2. **Configure Broadcasting**:
   ```env
   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=local
   PUSHER_APP_KEY=local
   PUSHER_APP_SECRET=local
   PUSHER_HOST=ws.kreasi-kita.my.id
   PUSHER_PORT=443
   PUSHER_SCHEME=https
   ```

3. **Publish WebSocket config**:
   ```bash
   php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
   ```

## Cloudflare Tunnel Setup

### 1. Install Cloudflared
```bash
# On your local machine or CI/CD
curl -L https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 -o cloudflared
chmod +x cloudflared
```

### 2. Create Tunnel
```bash
cloudflared tunnel create kreasi-kita-tunnel
```

### 3. Configure DNS
```bash
cloudflared tunnel route dns kreasi-kita-tunnel kreasi-kita.my.id
```

### 4. Update Tunnel Config
Edit `cloudflare-tunnel.yml`:
```yaml
tunnel: kreasi-kita-tunnel
credentials-file: /path/to/kreasi-kita-tunnel.json

ingress:
  - hostname: kreasi-kita.my.id
    service: https://your-coolify-app-url
    originRequest:
      httpHostHeader: kreasi-kita.my.id
      noTLSVerify: true
  - service: http_status:404
```

### 5. Run Tunnel
```bash
cloudflared tunnel --config cloudflare-tunnel.yml run
```

## File Structure

```
├── Dockerfile                    # Coolify-optimized container
├── docker-compose.yml            # Simplified for Coolify
├── coolify-deploy.sh             # Deployment preparation script
├── docker/
│   ├── nginx/
│   │   └── coolify.conf          # Nginx config for single container
│   ├── php/
│   │   ├── php-fpm.conf          # PHP-FPM configuration
│   │   └── local.ini             # PHP settings
│   └── supervisord.conf          # Process management
├── .env.docker                   # Environment template
└── COOLIFY_README.md            # This file
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Check database service is running in Coolify
   - Verify environment variables match Coolify service details
   - Check network connectivity between services

2. **Redis Connection Failed**
   - Verify Redis service is running
   - Check REDIS_HOST and REDIS_PORT variables
   - Fallback to file-based cache if needed:
     ```env
     CACHE_DRIVER=file
     SESSION_DRIVER=file
     QUEUE_CONNECTION=sync
     ```

3. **Assets Not Loading**
   - Ensure `npm run build` completed successfully
   - Check if public/build directory exists
   - Verify APP_URL is correctly set

4. **Livewire Not Working**
   - Check browser console for JavaScript errors
   - Verify CSRF token is valid
   - Ensure WebSocket connection (if using real-time features)

### Logs and Monitoring

1. **Application Logs**:
   ```bash
   # In Coolify container
   tail -f storage/logs/laravel.log
   ```

2. **Nginx Logs**:
   ```bash
   # Check access and error logs
   tail -f /var/log/nginx/access.log
   tail -f /var/log/nginx/error.log
   ```

3. **Queue Monitoring**:
   ```bash
   # Check queue worker status
   php artisan queue:monitor
   ```

## Performance Optimization

### 1. Laravel Optimizations
The deployment script automatically runs:
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

### 2. Database Optimization
- Use Redis for cache and sessions
- Enable MySQL query cache
- Optimize database indexes

### 3. Asset Optimization
- Frontend assets are pre-built during deployment
- Nginx serves static files with caching headers
- Gzip compression enabled

## Security Considerations

1. **Environment Variables**: Store sensitive data in Coolify environment variables
2. **Database Access**: Limit database access to application container only
3. **HTTPS**: Always use HTTPS in production (handled by Coolify/Cloudflare)
4. **Regular Updates**: Keep dependencies and base images updated

## Support

For issues related to:
- **Coolify**: Check Coolify documentation and community
- **Laravel/Livewire**: Check Laravel and Livewire documentation
- **Cloudflare Tunnel**: Check Cloudflare documentation
- **Docker**: Review container logs and configuration

## Additional Resources

- [Coolify Documentation](https://coolify.io/docs)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Livewire Documentation](https://laravel-livewire.com/docs)
- [Cloudflare Tunnel Guide](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps)