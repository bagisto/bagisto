# Bagisto development image — PHP 8.3 FPM + Nginx + Supervisor
FROM php:8.3-fpm-bookworm

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        git \
        nginx \
        supervisor \
        unzip \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        calendar \
        exif \
        gd \
        intl \
        mbstring \
        pdo_mysql \
        soap \
        sockets \
        zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/nginx.conf /etc/nginx/conf.d/bagisto.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-bagisto.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-bagisto.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/bagisto.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN rm -f /etc/nginx/sites-enabled/default \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p /run/php /var/log/supervisor

WORKDIR /var/www/html

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
