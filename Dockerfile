# Stage 1: compile frontend assets
FROM node:20-alpine AS node
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js postcss.config.cjs tailwind.config.cjs ./
RUN npm run build

# Stage 2: install PHP dependencies
FROM composer:2 AS vendor
WORKDIR /app
RUN apk add --no-cache \
    libzip-dev \
    zlib-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install \
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
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# copy application source
COPY . .
# copy built assets and vendor from previous stages
COPY --from=node /app/public/build ./public/build
COPY --from=vendor /app/vendor ./vendor
RUN php artisan optimize

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["frankenphp", "--config", "/etc/caddy/Caddyfile", "--adapter", "caddyfile"]
