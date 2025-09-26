#!/bin/bash

# Complete SSL Setup Script for your-domain.com
echo "=== SSL Setup untuk your-domain.com ==="
echo "Email: your-email@example.com"
echo ""

# Set domain and email
DOMAIN="your-domain.com"
EMAIL="your-email@example.com"

echo "1. Memastikan container berjalan..."
docker compose up -d

echo "2. Menunggu container siap..."
sleep 10

echo "3. Menginstall certbot di nginx container..."
docker compose exec nginx sh -c "apk add --no-cache certbot certbot-nginx"

echo "4. Memperoleh SSL certificate..."
docker compose exec nginx certbot --nginx -d $DOMAIN --email $EMAIL --agree-tos --non-interactive --redirect

echo "5. Testing SSL..."
echo "Testing HTTP redirect..."
curl -I http://$DOMAIN

echo ""
echo "Testing HTTPS..."
curl -I https://$DOMAIN

echo ""
echo "=== SSL Setup Selesai! ==="
echo "Website sekarang dapat diakses di:"
echo "- HTTP: http://$DOMAIN (akan redirect ke HTTPS)"
echo "- HTTPS: https://$DOMAIN"
echo ""
echo "Untuk auto-renewal, jalankan:"
echo "crontab -e"
echo "Dan tambahkan:"
echo "0 12 * * * cd /var/www/KerajinanKita && docker compose exec nginx certbot renew --quiet"
