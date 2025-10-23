# Use PHP 8.3 image with common extensions
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set proper permissions for Laravel storage and bootstrap
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
