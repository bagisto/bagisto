# =============================================================================
# Bagisto Production Image — OpenLiteSpeed (Single-stage)
# Single container: OpenLiteSpeed + lsphp 8.3 (LSAPI) + MySQL 8.0 + Supervisor
#
# Default mode  : Internal MySQL (ready-to-boot, Docker Hub style)
# Override mode : Set DB_HOST to an external address to skip internal MySQL
#
# Bagisto is FULLY INSTALLED at build time — migrations, seeding, and all.
# The image boots instantly with no first-run setup needed.
#
# Build context is `docker/production/`:
#   docker build -f litespeed/Dockerfile -t bagisto:2.4.7-litespeed .
# =============================================================================

FROM ubuntu:24.04

ARG BAGISTO_VERSION=v2.4.7
ARG LSPHP_VERSION=83

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC


# ---------------------------------------------------------------------------
# System packages + MySQL 8.0
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        apt-transport-https \
        ca-certificates \
        curl \
        git \
        gnupg \
        gosu \
        imagemagick \
        mysql-server \
        nano \
        supervisor \
        unzip \
        wget \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# OpenLiteSpeed + lsphp (native LSAPI PHP)
# repo.litespeed.sh adds the LiteSpeed apt repository.
# ---------------------------------------------------------------------------
RUN wget -qO - https://repo.litespeed.sh | bash \
    && apt-get update && apt-get install -y \
        openlitespeed \
        lsphp${LSPHP_VERSION} \
        lsphp${LSPHP_VERSION}-common \
        lsphp${LSPHP_VERSION}-curl \
        lsphp${LSPHP_VERSION}-imagick \
        lsphp${LSPHP_VERSION}-intl \
        lsphp${LSPHP_VERSION}-mysql \
        lsphp${LSPHP_VERSION}-opcache \
    && rm -rf /var/lib/apt/lists/*

ENV PATH=/usr/local/lsws/lsphp${LSPHP_VERSION}/bin:$PATH

# Expose lsphp's CLI as `php` so artisan / build-install.sh can run it.
RUN ln -sf /usr/local/lsws/lsphp${LSPHP_VERSION}/bin/php /usr/local/bin/php


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
# Prepare .env with internal-MySQL defaults
# ---------------------------------------------------------------------------
RUN cp .env.example .env \
    && sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env \
    && sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env \
    && sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env \
    && sed -i 's/^DB_DATABASE=.*/DB_DATABASE=bagisto/' .env \
    && sed -i 's/^DB_USERNAME=.*/DB_USERNAME=bagisto/' .env \
    && sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=bagisto/' .env \
    && sed -i 's|^APP_URL=.*|APP_URL=http://localhost|' .env


# ---------------------------------------------------------------------------
# Install Bagisto at BUILD TIME (starts MySQL, migrates + seeds, bakes data in)
# ---------------------------------------------------------------------------
COPY shared/mysql-init.sql /docker-entrypoint-initdb.d/init.sql
COPY shared/build-install.sh /tmp/build-install.sh
RUN chmod +x /tmp/build-install.sh && bash /tmp/build-install.sh && rm /tmp/build-install.sh


# ---------------------------------------------------------------------------
# PHP configuration (lsphp — LSAPI + CLI share the same binary)
# ---------------------------------------------------------------------------
COPY shared/php.ini /usr/local/lsws/lsphp${LSPHP_VERSION}/etc/php/8.3/litespeed/php.ini


# ---------------------------------------------------------------------------
# OpenLiteSpeed configuration
# ---------------------------------------------------------------------------
COPY litespeed/config/httpd_config.conf /usr/local/lsws/conf/httpd_config.conf
COPY litespeed/config/vhosts/bagisto.conf /usr/local/lsws/conf/vhosts/bagisto.conf
RUN chown -R www-data:www-data /usr/local/lsws/conf /usr/local/lsws/logs


# ---------------------------------------------------------------------------
# Supervisor configuration
# ---------------------------------------------------------------------------
COPY litespeed/config/supervisord.conf /etc/supervisor/conf.d/bagisto.conf


# ---------------------------------------------------------------------------
# Runtime directories
# ---------------------------------------------------------------------------
RUN mkdir -p /var/log/supervisor /var/log/bagisto /tmp/lshttpd


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
    && rm -rf /var/lib/apt/lists/* /tmp/*.tgz /var/tmp/*


EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
