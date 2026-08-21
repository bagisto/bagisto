# =============================================================================
# Bagisto Production Image — Apache (Single-stage)
# Single container: Apache 2 (mod_php) + PHP 8.3 + the bundled database + Supervisor
#
# Default mode  : the bundled database (ready to boot, Docker Hub style)
# Override mode : set DB_HOST to an external address to use that instead
#
# Which database is bundled is chosen with --build-arg DB_ENGINE=mysql|mariadb.
# Everything database-specific lives in shared/db/<engine>/, so this file is
# the same for either one.
#
# Bagisto is FULLY INSTALLED at build time — migrations, seeding, and all.
# The image boots instantly with no first-run setup needed.
#
# Build context is `docker/production/`:
#   docker build -f apache/Dockerfile -t bagisto:2.4.7-apache .
# =============================================================================

FROM ubuntu:24.04

ARG BAGISTO_VERSION=v2.4.7
ARG DB_ENGINE=mysql
ARG PHP_VERSION=8.3

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC


# ---------------------------------------------------------------------------
# System packages + PHP PPA + Apache + MySQL 8.0
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        git \
        gnupg \
        nano \
        software-properties-common \
        unzip \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update && apt-get install -y \
        apache2 \
        imagemagick \
        supervisor \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# PHP 8.3 (CLI for artisan/build + Apache mod_php for serving) + extensions
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        libapache2-mod-php${PHP_VERSION} \
        php${PHP_VERSION}-bcmath \
        php${PHP_VERSION}-calendar \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-exif \
        php${PHP_VERSION}-gd \
        php${PHP_VERSION}-gmp \
        php${PHP_VERSION}-imagick \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-mysql \
        php${PHP_VERSION}-pdo \
        php${PHP_VERSION}-soap \
        php${PHP_VERSION}-sockets \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-zip \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# The bundled database server
#
# Only the chosen engine's server is installed. Both PHP drivers are present
# regardless, so an image can still be pointed at an external server of the
# other kind without rebuilding.
# ---------------------------------------------------------------------------
RUN apt-get update && if [ "$DB_ENGINE" = "mariadb" ]; then \
        apt-get install -y mariadb-server mariadb-client; \
    else \
        apt-get install -y mysql-server mysql-client; \
    fi \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# Composer
# ---------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer


# ---------------------------------------------------------------------------
# Clone Bagisto + Composer install
# ---------------------------------------------------------------------------
WORKDIR /var/www/bagisto

RUN git clone --depth 1 --branch ${BAGISTO_VERSION} \
        https://github.com/bagisto/bagisto.git . \
    && composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
    && rm -rf /root/.composer/cache


# ---------------------------------------------------------------------------
# Prepare .env with the bundled-database defaults
#
# DB_CONNECTION and DB_PORT are engine-specific and are set further down,
# next to the install that actually uses them.
# ---------------------------------------------------------------------------
RUN cp .env.example .env \
    && sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env \
    && sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env \
    && sed -i 's/^DB_DATABASE=.*/DB_DATABASE=bagisto/' .env \
    && sed -i 's/^DB_USERNAME=.*/DB_USERNAME=bagisto/' .env \
    && sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=bagisto/' .env \
    && sed -i 's|^APP_URL=.*|APP_URL=http://localhost|' .env


# ---------------------------------------------------------------------------
# Install Bagisto at BUILD TIME (starts MySQL, migrates + seeds, bakes data in)
# ---------------------------------------------------------------------------
COPY shared/db/${DB_ENGINE}/ /usr/local/share/bagisto/db/
COPY shared/build-install.sh /tmp/build-install.sh
RUN mkdir -p /docker-entrypoint-initdb.d \
    && cp /usr/local/share/bagisto/db/init.sql /docker-entrypoint-initdb.d/init.sql \
    && sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=$(if [ \"$DB_ENGINE\" = mariadb ]; then echo mariadb; else echo mysql; fi)/" /var/www/bagisto/.env \
    && sed -i "s/^DB_PORT=.*/DB_PORT=3306/" /var/www/bagisto/.env \
    && chmod +x /tmp/build-install.sh \
    && DB_ENGINE=${DB_ENGINE} bash /tmp/build-install.sh \
    && rm /tmp/build-install.sh


# ---------------------------------------------------------------------------
# PHP configuration (applied to both the Apache SAPI and the CLI)
# ---------------------------------------------------------------------------
COPY shared/php.ini /etc/php/${PHP_VERSION}/apache2/conf.d/99-production.ini
COPY shared/php.ini /etc/php/${PHP_VERSION}/cli/conf.d/99-production.ini


# ---------------------------------------------------------------------------
# Apache configuration
# ---------------------------------------------------------------------------
COPY apache/config/vhost.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers expires \
    && a2dissite 000-default >/dev/null 2>&1 || true \
    && a2ensite 000-default \
    && rm -f /var/www/html/index.html


# ---------------------------------------------------------------------------
# Supervisor configuration
# ---------------------------------------------------------------------------
COPY apache/config/supervisord.conf /etc/supervisor/conf.d/bagisto.conf
COPY shared/db/${DB_ENGINE}/supervisord.conf /etc/supervisor/conf.d/database.conf


# ---------------------------------------------------------------------------
# Runtime directories
# ---------------------------------------------------------------------------
RUN mkdir -p /var/log/supervisor /var/log/bagisto


# ---------------------------------------------------------------------------
# Permissions
# ---------------------------------------------------------------------------
RUN chown -R www-data:www-data /var/www/bagisto \
    && chmod -R 775 storage bootstrap/cache \
    && find storage bootstrap/cache -type d -exec chmod g+s {} +


# ---------------------------------------------------------------------------
# Entrypoint
# ---------------------------------------------------------------------------
COPY shared/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh


# ---------------------------------------------------------------------------
# Cleanup
# ---------------------------------------------------------------------------
RUN apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
