FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  zip \
  unzip \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmcrypt-dev \
  libgd-dev \
  jpegoptim optipng pngquant gifsicle \
  vim \
  supervisor \
  cron \
  libicu-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Enable OPcache and Sodium extensions (usually included by default)
RUN docker-php-ext-enable opcache sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/www www
RUN mkdir -p /home/www/.composer && \
  chown -R www:www /home/www

# Fix git ownership issue
RUN git config --global --add safe.directory /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Set permissions for Laravel
RUN chown -R www:www /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]