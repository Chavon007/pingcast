FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    sqlite-dev \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev \
    supervisor

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    pdo_pgsql \
    pgsql \
    mysqli \
    bcmath \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel application
COPY . .

# Create SQLite database file if it doesn't exist
RUN mkdir -p database && touch database/database.sqlite

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear stale caches so modules/routes/commands are always fresh
RUN composer dump-autoload -o
RUN rm -f bootstrap/cache/modules.php bootstrap/cache/packages.php bootstrap/cache/services.php bootstrap/cache/config.php bootstrap/cache/routes-v7.php

# Create required directories
RUN mkdir -p \
    /run/nginx \
    /var/run/php \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    database


# Copy nginx configuration
COPY nginx/default.conf /etc/nginx/http.d/default.conf

# Expose Render port
EXPOSE 10000

# Start PHP-FPM + Nginx
CMD php artisan migrate --force && php-fpm -D && nginx -g "daemon off;"