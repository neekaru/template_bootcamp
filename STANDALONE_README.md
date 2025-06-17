# Standalone Laravel Deployment with External Services

This guide explains how to deploy your Laravel application independently while using Coolify only for external services (database, Redis), then connecting everything via Cloudflare Tunnel.

## Architecture Overview

```
┌─────────────────────┐    ┌─────────────────────┐    ┌─────────────────────┐
│   Your Server       │    │     Coolify         │    │    Cloudflare       │
│                     │    │                     │    │                     │
│  ┌──────────────┐   │    │  ┌──────────────┐   │    │  ┌──────────────┐   │
│  │   Laravel    │   │    │  │    MySQL     │   │    │  │    Tunnel    │   │
│  │  App:3000    │◄──┼────┼──┤   Database   │   │    │  │              │   │
│  └──────────────┘   │    │  └──────────────┘   │    │  │              │   │
│                     │    │                     │    │  │              │   │
│  ┌──────────────┐   │    │  ┌──────────────┐   │    │  │              │   │
│  │ Cloudflared  │───┼────┼──┤     Redis    │   │    │  │              │   │
│  │   Client     │   │    │  │    Cache     │   │    │  │              │   │
│  └──────────────┘   │    │  └──────────────┘   │    │  └──────────────┘   │
└─────────────────────┘    └─────────────────────┘    └─────────────────────┘
          │                                                       │
          └───────────────────────────────────────────────────────┘
                    kreasi-kita.my.id (Public Domain)
```

## Benefits of This Approach

✅ **Simple Application Deployment**: No complex Coolify configuration for your app
✅ **Managed External Services**: Let Coolify handle database and Redis reliability
✅ **Easy Updates**: Update your app independently without affecting services
✅ **Cost Effective**: Use Coolify's free tier for services, host app anywhere
✅ **Full Control**: Complete control over your application environment

## Prerequisites

1. **Your own server** (VPS, dedicated server, or local machine)
2. **Docker and Docker Compose** installed
3. **Coolify instance** for external services only
4. **Cloudflare account** with domain `kreasi-kita.my.id`

## Setup Steps

### 1. Set Up External Services in Coolify

#### Create MySQL Database Service
1. Log into your Coolify dashboard
2. Create a new **MySQL service**
3. Note the connection details:
   - Host: `your-coolify-domain.com` or IP
   - Port: `3306`
   - Database: `laravel_db`
   - Username: `laravel_user`
   - Password: `secure_password`

#### Create Redis Service
1. Create a new **Redis service** in Coolify
2. Note the connection details:
   - Host: `your-coolify-domain.com` or IP
   - Port: `6379`
   - Password: `redis_password` (if set)

### 2. Deploy Your Laravel Application

#### Clone and Setup
```bash
git clone your-laravel-repo
cd your-laravel-project
```

#### Run Deployment Script
```bash
chmod +x standalone-deploy.sh
./standalone-deploy.sh
```

#### Configure Environment
Edit your `.env` file with external service details:
```env
# Application
APP_NAME="Kreasi Kita"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://kreasi-kita.my.id

# External Database (from Coolify)
DB_CONNECTION=mysql
DB_HOST=your-coolify-domain.com
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password

# External Redis (from Coolify)
REDIS_HOST=your-coolify-domain.com
REDIS_PORT=6379
REDIS_PASSWORD=redis_password

# Laravel Settings
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
BROADCAST_DRIVER=pusher

# WebSocket Configuration
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_HOST=ws.kreasi-kita.my.id
PUSHER_PORT=443
PUSHER_SCHEME=https
```

#### Restart Application
```bash
docker-compose down
docker-compose up -d
```

### 3. Set Up Cloudflare Tunnel

#### Install Cloudflared
```bash
# Download cloudflared
curl -L https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64 -o cloudflared
chmod +x cloudflared
sudo mv cloudflared /usr/local/bin/
```

#### Create Tunnel
```bash
# Login to Cloudflare
cloudflared tunnel login

# Create tunnel
cloudflared tunnel create kreasi-kita-tunnel

# Configure DNS
cloudflared tunnel route dns kreasi-kita-tunnel kreasi-kita.my.id
cloudflared tunnel route dns kreasi-kita-tunnel ws.kreasi-kita.my.id
```

#### Configure and Run Tunnel
The `cloudflare-tunnel.yml` is already configured for your domain. Run:
```bash
cloudflared tunnel --config cloudflare-tunnel.yml run
```

### 4. Set Up as System Service (Optional)

#### Create Systemd Service for Application
```bash
sudo tee /etc/systemd/system/kreasi-kita-app.service > /dev/null <<EOF
[Unit]
Description=Kreasi Kita Laravel Application
Requires=docker.service
After=docker.service

[Service]
Type=oneshot
RemainAfterExit=yes
WorkingDirectory=/path/to/your/application
ExecStart=/usr/local/bin/docker-compose up -d
ExecStop=/usr/local/bin/docker-compose down
TimeoutStartSec=0

[Install]
WantedBy=multi-user.target
EOF
```

#### Create Systemd Service for Cloudflare Tunnel
```bash
sudo tee /etc/systemd/system/cloudflared-kreasi-kita.service > /dev/null <<EOF
[Unit]
Description=Cloudflare Tunnel for Kreasi Kita
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/path/to/your/application
ExecStart=/usr/local/bin/cloudflared tunnel --config cloudflare-tunnel.yml run
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
EOF
```

#### Enable Services
```bash
sudo systemctl daemon-reload
sudo systemctl enable kreasi-kita-app.service
sudo systemctl enable cloudflared-kreasi-kita.service
sudo systemctl start kreasi-kita-app.service
sudo systemctl start cloudflared-kreasi-kita.service
```

## File Structure

```
├── standalone-deploy.sh          # Deployment automation script
├── docker-compose.yml            # Standalone app configuration
├── Dockerfile                    # Application container
├── cloudflare-tunnel.yml         # Tunnel configuration
├── .env.docker                   # Environment template
├── docker/
│   ├── nginx/
│   │   ├── coolify.conf          # Nginx configuration
│   │   ├── generate-ssl.sh       # SSL certificate generator
│   │   └── ssl/                  # SSL certificates
│   ├── php/
│   │   ├── php-fpm.conf          # PHP-FPM configuration
│   │   └── local.ini             # PHP settings
│   └── supervisord.conf          # Process management
└── STANDALONE_README.md          # This file
```

## Management Commands

### Application Management
```bash
# View application status
docker-compose ps

# View application logs
docker-compose logs -f

# Restart application
docker-compose restart

# Update application
git pull
docker-compose build
docker-compose up -d

# Run Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan cache:clear
```

### Service Monitoring
```bash
# Check systemd services
sudo systemctl status kreasi-kita-app.service
sudo systemctl status cloudflared-kreasi-kita.service

# View service logs
sudo journalctl -u kreasi-kita-app.service -f
sudo journalctl -u cloudflared-kreasi-kita.service -f
```

## Troubleshooting

### Common Issues

#### 1. Database Connection Failed
```bash
# Test database connection
docker-compose exec app php artisan tinker
# In tinker: DB::connection()->getPdo();

# Check if database service is accessible
telnet your-coolify-domain.com 3306
```

#### 2. Redis Connection Failed
```bash
# Test Redis connection
docker-compose exec app php artisan tinker
# In tinker: Redis::ping();

# Check Redis service
telnet your-coolify-domain.com 6379
```

#### 3. Cloudflare Tunnel Issues
```bash
# Check tunnel status
cloudflared tunnel info kreasi-kita-tunnel

# Test local application
curl http://localhost:3000/health

# Check tunnel logs
sudo journalctl -u cloudflared-kreasi-kita.service -f
```

#### 4. Application Not Loading
```bash
# Check application logs
docker-compose logs app

# Check nginx configuration
docker-compose exec app nginx -t

# Verify permissions
docker-compose exec app ls -la storage/
```

### Performance Optimization

#### 1. Laravel Optimizations
```bash
# Run inside container
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan optimize
```

#### 2. Database Optimizations
- Enable query caching in MySQL
- Use Redis for session storage
- Optimize database indexes

#### 3. Caching Strategy
```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## Security Considerations

1. **Database Security**:
   - Use strong passwords for database connections
   - Restrict database access to your application IP only
   - Enable SSL for database connections

2. **Application Security**:
   - Keep environment variables secure
   - Regular security updates for dependencies
   - Use HTTPS everywhere (enforced by Cloudflare)

3. **Server Security**:
   - Keep server OS updated
   - Configure firewall (only allow necessary ports)
   - Use SSH key authentication

## Backup Strategy

### Application Backup
```bash
# Create application backup
tar -czf kreasi-kita-backup-$(date +%Y%m%d).tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  --exclude=storage/logs \
  .
```

### Database Backup
```bash
# Database backup (run on Coolify server or remotely)
mysqldump -h your-coolify-domain.com -u laravel_user -p laravel_db > backup.sql
```

## Cost Analysis

### Your Server Costs:
- VPS/Server: $5-20/month (depending on provider and specs)
- Domain: $10-15/year
- Cloudflare: Free

### Coolify External Services:
- Database: Free (with resource limits) or paid plans
- Redis: Free (with resource limits) or paid plans

**Total Estimated Cost**: $5-25/month + domain fees

## Support and Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Cloudflare Tunnel Documentation](https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/)
- [Coolify Documentation](https://coolify.io/docs)

This setup gives you the best of both worlds: simple application deployment with managed external services!