FROM php:8.3-fpm-alpine

RUN addgroup -g 1000 laravel && \
    adduser -G laravel -g laravel -s /bin/sh -D laravel


# Install dependencies for PHP extensions
RUN apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    oniguruma-dev


# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    mysqli \
    bcmath \
    opcache


RUN mkdir -p /var/www/html /var/run/php && \
    chown -R laravel:laravel /var/www/html /var/run/php