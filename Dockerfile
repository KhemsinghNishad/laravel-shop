FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . /var/www/html

WORKDIR /var/www/html

# Permissions
RUN chmod -R 777 storage bootstrap/cache

# Install Laravel dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Create storage link
RUN php artisan storage:link || true

# Apache Document Root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]
