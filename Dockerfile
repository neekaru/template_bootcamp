# Stage 1: compile frontend assets
FROM node:20-alpine AS node
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js postcss.config.cjs tailwind.config.cjs ./
RUN npm run build

# Stage 2: install PHP dependencies
FROM composer:3 AS vendor
WORKDIR /app
RUN install-php-extensions \
 pdo_mysql \
 gd \
 intl \
 zip \
 opcache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 3: production image using FrankenPHP
FROM dunglas/frankenphp:1-php8.3
WORKDIR /app

# Install PHP extensions
RUN install-php-extensions \
 pdo_mysql \
 gd \
 intl \
 zip \
 opcache


# copy application source
COPY . .
# copy built assets and vendor from previous stages
COPY --from=node /app/public/build ./public/build
COPY --from=vendor /app/vendor ./vendor
RUN php artisan optimize

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["frankenphp", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
