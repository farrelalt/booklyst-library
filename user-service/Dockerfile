FROM php:8.2-fpm-alpine

WORKDIR /var/www

# Install dependency sistem
RUN apk add --no-cache \
    git curl zip unzip bash \
    libpng-dev oniguruma-dev libxml2-dev \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Redis extension untuk PHP
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua file project
COPY . .

# Install PHP packages
RUN composer install --no-dev --optimize-autoloader --no-interaction 2>/dev/null || true

# Set permission untuk Laravel storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]