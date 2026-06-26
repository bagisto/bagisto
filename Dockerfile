# =============================================================================
# Bagisto — Imagem para deploy em cloud (Render, Railway, Fly.io, etc.)
#
# Diferente de docker/production/Dockerfile (que clona do repo oficial),
# esta imagem copia o código-fonte deste repositório diretamente.
#
# Modo padrão: MySQL embutido (dados perdidos ao reiniciar — bom para testes)
# Modo externo: defina DB_HOST para usar banco externo e ter dados persistentes
# =============================================================================

FROM ubuntu:24.04

ARG PHP_VERSION=8.3
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# ---------------------------------------------------------------------------
# Pacotes do sistema + PPA PHP + MySQL 8.0
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg \
        software-properties-common \
        unzip \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update && apt-get install -y \
        imagemagick \
        libfreetype6-dev \
        libgmp-dev \
        libicu-dev \
        libjpeg-turbo8-dev \
        libmagickwand-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        mysql-server \
        nginx \
        supervisor \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# PHP 8.3 + extensões
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-calendar \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-dev \
        php${PHP_VERSION}-exif \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-gmp \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-pdo \
        php${PHP_VERSION}-soap \
        php${PHP_VERSION}-sockets \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip \
        php-pear \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# Extensão Imagick via PECL
# ---------------------------------------------------------------------------
RUN pecl channel-update pecl.php.net \
    && mkdir -p /tmp/imagick-build && cd /tmp/imagick-build \
    && pecl download imagick \
    && tar xzf imagick-*.tgz \
    && cd imagick-* \
    && phpize${PHP_VERSION} \
    && ./configure --with-php-config=/usr/bin/php-config${PHP_VERSION} \
    && make -j$(nproc) \
    && make install \
    && echo "extension=imagick.so" > /etc/php/${PHP_VERSION}/mods-available/imagick.ini \
    && phpenmod -v ${PHP_VERSION} imagick \
    && rm -rf /tmp/imagick-build

RUN apt-get purge -y php${PHP_VERSION}-dev php-pear \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# Composer
# ---------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# ---------------------------------------------------------------------------
# Código-fonte do repositório
# ---------------------------------------------------------------------------
WORKDIR /var/www/bagisto

COPY . .

RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
    && rm -rf /root/.composer/cache

# ---------------------------------------------------------------------------
# Prepara .env com defaults para MySQL interno
# ---------------------------------------------------------------------------
RUN cp .env.example .env \
    && sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env \
    && sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env \
    && sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env \
    && sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env \
    && sed -i 's/^DB_DATABASE=.*/DB_DATABASE=bagisto/' .env \
    && sed -i 's/^DB_USERNAME=.*/DB_USERNAME=bagisto/' .env \
    && sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=bagisto/' .env \
    && sed -i 's|^APP_URL=.*|APP_URL=http://localhost|' .env

# ---------------------------------------------------------------------------
# Instala Bagisto em tempo de build (com MySQL temporário)
# ---------------------------------------------------------------------------
COPY docker/production/mysql-init.sql /docker-entrypoint-initdb.d/init.sql
COPY docker/production/build-install.sh /tmp/build-install.sh
RUN chmod +x /tmp/build-install.sh && bash /tmp/build-install.sh && rm /tmp/build-install.sh

# ---------------------------------------------------------------------------
# Configuração PHP, Nginx e Supervisor
# ---------------------------------------------------------------------------
COPY docker/production/php.ini /etc/php/${PHP_VERSION}/fpm/conf.d/99-production.ini
COPY docker/production/php-fpm.conf /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf

RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf
COPY docker/production/nginx.conf /etc/nginx/conf.d/bagisto.conf

COPY docker/production/supervisord.conf /etc/supervisor/conf.d/bagisto.conf

# ---------------------------------------------------------------------------
# Diretórios de runtime e permissões
# ---------------------------------------------------------------------------
RUN mkdir -p /run/php /var/log/supervisor /var/log/bagisto \
    && chown -R www-data:www-data /var/www/bagisto \
    && chmod -R 775 storage bootstrap/cache \
    && find storage bootstrap/cache -type d -exec chmod g+s {} +

# ---------------------------------------------------------------------------
# Entrypoint
# ---------------------------------------------------------------------------
COPY docker/production/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# ---------------------------------------------------------------------------
# Limpeza final
# ---------------------------------------------------------------------------
RUN apt-get purge -y git \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
