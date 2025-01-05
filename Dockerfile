# main image
FROM php:8.3-apache

# installing dependencies
RUN apt-get update && apt-get install -y \
    git \
    ffmpeg \
    libfreetype6-dev \
    libicu-dev \
    libgmp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libzip-dev \
    unzip \
    zlib1g-dev

# configuring php extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-configure intl

# installing php extension
RUN docker-php-ext-install bcmath calendar exif gd gmp intl mysqli pdo pdo_mysql zip

# installing composer
COPY --from=composer:2.7 /usr/bin/composer /usr/local/bin/composer

# installing node js
COPY --from=node:23 /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:23 /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

# Configure Apache to listen on port 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# setting apache
COPY ./.configs/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# setting work directory
WORKDIR /var/www/html

# Create base directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy application code
COPY . /var/www/html

# Set permissions for application files
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type f -exec chmod 664 {} \; \
    && find /var/www/html -type d -exec chmod 775 {} \;

# Install dependencies
RUN composer update league/flysystem-aws-s3-v3 --with-dependencies && \
    composer require bagisto/graphql-api && \
    composer require mll-lab/laravel-graphql-playground && \
    composer install --no-dev --optimize-autoloader

# Clear all caches and create storage link
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    php artisan optimize:clear && \
    rm -f public/storage && \
    php artisan storage:link

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 8080
EXPOSE 8080

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"] 