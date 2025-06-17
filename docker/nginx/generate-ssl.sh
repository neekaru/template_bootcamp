#!/bin/bash

# Create SSL directory if it doesn't exist
mkdir -p docker/nginx/ssl

# Generate self-signed SSL certificate for development
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout docker/nginx/ssl/key.pem \
    -out docker/nginx/ssl/cert.pem \
    -subj "/C=US/ST=State/L=City/O=Organization/OU=OrgUnit/CN=localhost"

echo "SSL certificates generated successfully!"
echo "Key: docker/nginx/ssl/key.pem"
echo "Cert: docker/nginx/ssl/cert.pem"