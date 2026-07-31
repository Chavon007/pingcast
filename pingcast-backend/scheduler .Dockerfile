FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    unzip \
    sqlite-dev \
    postgresql-dev \
    libzip-dev \
    oniguruma-dev

RUN docker-php-ext-install \
    pdo \
    pdo_sqlite \
    pdo_pgsql \
    pgsql \
    mysqli \
    bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chmod +x start-scheduler.sh

CMD ["./start-scheduler.sh"]