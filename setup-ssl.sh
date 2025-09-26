#!/bin/bash

# SSL Setup Script
echo "Setting up SSL for your domain..."

# Set your domain and email
DOMAIN="your-domain.com"  # Change this to your actual domain
EMAIL="admin@neekaru.com"

# Create environment file
cat > .env.docker << EOF
DOMAIN=$DOMAIN
EMAIL=$EMAIL
EOF

echo "Environment file created with:"
echo "DOMAIN=$DOMAIN"
echo "EMAIL=$EMAIL"
echo ""
echo "To setup SSL:"
echo "1. Change DOMAIN in .env.docker to your actual domain"
echo "2. Run: docker compose up -d"
echo "3. Run: docker compose exec nginx sh -c 'apk add --no-cache certbot certbot-nginx && certbot --nginx -d $DOMAIN --email $EMAIL --agree-tos --non-interactive --redirect'"
echo ""
echo "For auto-renewal, add this to your crontab:"
echo "0 12 * * * docker compose exec nginx certbot renew --quiet"
