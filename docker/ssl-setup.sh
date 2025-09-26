#!/bin/bash

# SSL Setup Script for Let's Encrypt
DOMAIN=${DOMAIN:-"your-domain.com"}
EMAIL=${EMAIL:-"your-email@example.com"}

echo "Setting up SSL for domain: $DOMAIN"
echo "Email: $EMAIL"

# Install certbot if not exists
if ! command -v certbot &> /dev/null; then
    echo "Installing certbot..."
    apt-get update
    apt-get install -y certbot python3-certbot-nginx
fi

# Create nginx config for domain
cat > /etc/nginx/sites-available/default << EOF
server {
    listen 80;
    server_name $DOMAIN;
    root /var/www/html/public;
    index index.php index.html index.htm;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Test nginx configuration
nginx -t

# Reload nginx
systemctl reload nginx

# Obtain SSL certificate
echo "Obtaining SSL certificate..."
certbot --nginx -d $DOMAIN --email $EMAIL --agree-tos --non-interactive --redirect

# Setup auto-renewal
echo "Setting up auto-renewal..."
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -

echo "SSL setup completed!"
