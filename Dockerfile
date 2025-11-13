# Base PHP image (FPM for Laravel)
FROM php:8.2-fpm

# Install system dependencies and PHP extensions required for Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpq-dev libicu-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip intl gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer from official Composer image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy Laravel project files into container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel storage and bootstrap cache folders
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose Render’s HTTP port
EXPOSE 10000

# Start Laravel built-in server
CMD php artisan serve --host=0.0.0.0 --port=10000
