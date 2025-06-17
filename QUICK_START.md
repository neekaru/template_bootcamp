# Quick Start Guide - Laravel with Livewire Docker Setup

Choose your deployment approach:

## 🚀 Option 1: Standalone Deployment (Recommended)
**Use Coolify only for database & Redis, deploy your app independently**

### Why Choose This?
- ✅ Simpler application deployment
- ✅ Full control over your application
- ✅ Easy updates and maintenance
- ✅ Use Coolify's free tier for services only

### Quick Setup
```bash
# 1. Deploy your application
./standalone-deploy.sh

# 2. Configure external services in .env
# Edit .env with your Coolify database and Redis details

# 3. Set up Cloudflare Tunnel
cloudflared tunnel create kreasi-kita-tunnel
cloudflared tunnel route dns kreasi-kita-tunnel kreasi-kita.my.id
cloudflared tunnel --config cloudflare-tunnel.yml run

# 4. Access your app
# https://kreasi-kita.my.id
```

### What You Need
- Your own server (VPS, local machine)
- Coolify instance for database & Redis
- Cloudflare account

📖 **[Complete Guide: STANDALONE_README.md](STANDALONE_README.md)**

---

## 🏢 Option 2: Full Coolify Deployment
**Deploy everything through Coolify**

### Why Choose This?
- ✅ Everything managed in one place
- ✅ Automatic scaling and monitoring
- ✅ Built-in CI/CD pipelines

### Quick Setup
```bash
# 1. Prepare for Coolify
./coolify-deploy.sh

# 2. Create application in Coolify
# - Port: 3000
# - Build Command: ./coolify-deploy.sh

# 3. Set up external services in Coolify
# - MySQL database
# - Redis cache

# 4. Configure environment variables in Coolify dashboard
```

📖 **[Complete Guide: COOLIFY_README.md](COOLIFY_README.md)**

---

## 📁 File Overview

### Core Files
- `Dockerfile` - Application container configuration
- `docker-compose.yml` - Service orchestration
- `cloudflare-tunnel.yml` - Tunnel configuration for `kreasi-kita.my.id`

### Deployment Scripts
- `standalone-deploy.sh` - For standalone deployment
- `coolify-deploy.sh` - For Coolify deployment
- `docker-validate.sh` - Environment validation

### Configuration
- `.env.docker` - Environment template
- `docker/nginx/coolify.conf` - Nginx configuration
- `docker/php/` - PHP-FPM settings
- `docker/supervisord.conf` - Process management

### Documentation
- `STANDALONE_README.md` - Standalone deployment guide
- `COOLIFY_README.md` - Coolify deployment guide
- `DOCKER_README.md` - General Docker information

## 🌐 Your Domain Configuration

All configurations are set up for **`kreasi-kita.my.id`**:
- Main site: `https://kreasi-kita.my.id`
- WebSocket: `wss://ws.kreasi-kita.my.id`
- API: `https://api.kreasi-kita.my.id`

## 🔧 Application Features

### ✅ Laravel Optimized
- PHP 8.2 with optimized settings
- Composer dependencies management
- Artisan command support

### ✅ Livewire Ready
- Real-time component updates
- WebSocket support for live features
- Optimized for interactive UI

### ✅ Frontend Compilation
- Node.js 18 for asset building
- Vite for fast development
- Pre-compiled assets for production

### ✅ Production Ready
- Nginx with caching and compression
- PHP-FPM with performance tuning
- Queue worker for background jobs
- Scheduler for automated tasks

### ✅ Cloudflare Tunnel Compatible
- Real IP detection
- WebSocket proxying
- SSL termination support

## 🚨 Port Configuration

**Application Port: 3000**
- Avoids conflicts with common Coolify ports (80, 8080, 8000)
- Works seamlessly with Cloudflare Tunnel
- Standard for Node.js applications

## 📞 Need Help?

1. **Validation Issues**: Run `./docker-validate.sh`
2. **Docker Problems**: Check `docker-compose logs`
3. **Database Issues**: Verify external service connections
4. **Cloudflare Issues**: Check tunnel configuration and DNS

## 🎯 Recommended Approach

For most users, we recommend **Option 1: Standalone Deployment** because:
- Easier to understand and manage
- Lower complexity for application updates
- Better separation of concerns
- More cost-effective for small to medium applications

Start with standalone deployment and migrate to full Coolify if you need advanced features like auto-scaling or complex CI/CD pipelines.