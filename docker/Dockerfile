# docker/Dockerfile

######## 1) Composer deps (cache)
FROM composer:2 AS composer_deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress

######## 2) Node build (vite)
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json vite.config.ts ./
COPY resources ./resources
RUN npm ci --silent
# Build akan dijalankan di stage final (butuh kode lengkap)

######## 3) Final app: FrankenPHP (php 8.3, alpine)
# Image berisi server HTTP sendiri, tanpa nginx/caddy di container app
FROM dunglas/frankenphp:1.2-php8.3-alpine AS app
WORKDIR /app

# System deps
RUN apk add --no-cache git icu-dev zlib-dev libzip-dev oniguruma-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev libxml2-dev \
    curl-dev

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip intl gd opcache

# Redis
RUN pecl install redis \
 && docker-php-ext-enable redis

# php.ini & opcache
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy source code
COPY . /app

# Composer (from deps) – re-run to generate optimized autoload w/ full code
COPY --from=composer_deps /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress \
 && php artisan package:discover --ansi

# Node untuk build asset production
RUN apk add --no-cache nodejs npm
COPY --from=assets /app/node_modules ./node_modules
RUN npm run build

# Permissions Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# FrankenPHP config:
# - Serve public/ on port 8080 (default) & run PHP via frankenphp fpm worker
ENV APP_RUNTIME=frankenphp
ENV SERVER_NAME=:8080
ENV DOCUMENT_ROOT=/app/public
EXPOSE 8080

# Healthcheck sederhana
HEALTHCHECK --interval=30s --timeout=3s \
  CMD wget -qO- http://127.0.0.1:8080/health || exit 1

CMD ["frankenphp", "run", "--config", "/usr/local/etc/php/php.ini", "-c", "production"]
