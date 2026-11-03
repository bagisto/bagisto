# ===========================================================================
# Bagisto Production Image — OpenLiteSpeed (Single-Stage)
#
# One container runs OpenLiteSpeed with lsphp 8.4 over LSAPI, the bundled database, a queue worker
# and cron, under Supervisor.
#
# Default mode  : the bundled database, ready to boot, Docker Hub style.
# Override mode : set DB_HOST to an external address to use that instead.
#
# Which database is bundled is chosen with --build-arg DB_ENGINE=mysql|mariadb|postgres.
# Everything database-specific lives in shared/db/<engine>/, so this file is
# the same for all of them.
#
# Bagisto is fully installed at build time, migrations and seeding included,
# so the image boots straight into a working store with no first-run setup.
#
# Build context is `docker/production/`:
#   docker build -f litespeed/Dockerfile -t bagisto:2.5.0-litespeed .
# ===========================================================================

FROM ubuntu:24.04

ARG BAGISTO_VERSION=v2.5.0
ARG DB_ENGINE=mysql
ARG LSPHP_VERSION=84

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC


# ---------------------------------------------------------------------------
# System Packages + PHP PPA
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
        apt-transport-https \
        ca-certificates \
        cron \
        curl \
        git \
        gnupg \
        gosu \
        imagemagick \
        nano \
        supervisor \
        unzip \
        wget \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# OpenLiteSpeed + lsphp
#
# The lsphp packages are LiteSpeed's native LSAPI PHP, from the apt
# repository that repo.litespeed.sh adds.
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
        lsphp${LSPHP_VERSION}-pgsql \
        lsphp${LSPHP_VERSION}-opcache \
    && rm -rf /var/lib/apt/lists/*

ENV PATH=/usr/local/lsws/lsphp${LSPHP_VERSION}/bin:$PATH

# Expose lsphp's CLI as `php`, so artisan and build-install.sh can run it.
RUN ln -sf /usr/local/lsws/lsphp${LSPHP_VERSION}/bin/php /usr/local/bin/php


# ---------------------------------------------------------------------------
# The Bundled Database Server
#
# Only the chosen engine's server is installed. Both PHP drivers are present
# regardless, so an image can still be pointed at an external server of the
# other kind without rebuilding.
# ---------------------------------------------------------------------------
COPY shared/db/${DB_ENGINE}/ /usr/local/share/bagisto/db/
RUN . /usr/local/share/bagisto/db/engine.sh \
    && apt-get update \
    && apt-get install -y $(db_server_packages) \
    && rm -rf /var/lib/apt/lists/*


# ---------------------------------------------------------------------------
# Composer
# ---------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer


# ---------------------------------------------------------------------------
# Clone Bagisto + Composer Install
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
# Prepare .env with the Bundled-Database Defaults
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
# Install Bagisto at Build Time
#
# The build-install.sh script starts the bundled database, creates its
# database and user, generates the application key, runs the Bagisto
# installer, seeds the demo products and builds the search indexes, then shuts
# the database down cleanly. The populated data directory is baked into this
# layer.
# ---------------------------------------------------------------------------
COPY shared/build-install.sh /tmp/build-install.sh
RUN mkdir -p /docker-entrypoint-initdb.d \
    && cp /usr/local/share/bagisto/db/init.sql /docker-entrypoint-initdb.d/init.sql \
    && chmod +x /usr/local/share/bagisto/db/*.sh \
    && . /usr/local/share/bagisto/db/engine.sh \
    && sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=$(db_connection)/" /var/www/bagisto/.env \
    && sed -i "s/^DB_PORT=.*/DB_PORT=$(db_default_port)/" /var/www/bagisto/.env \
    && chmod +x /tmp/build-install.sh \
    && DB_ENGINE=${DB_ENGINE} bash /tmp/build-install.sh \
    && rm /tmp/build-install.sh


# ---------------------------------------------------------------------------
# PHP Configuration
#
# The lsphp binary serves both LSAPI and the CLI, so one file configures both.
# ---------------------------------------------------------------------------
COPY shared/php.ini /tmp/php.ini
RUN cp /tmp/php.ini /usr/local/lsws/lsphp${LSPHP_VERSION}/etc/php/*/litespeed/php.ini \
    && rm /tmp/php.ini


# ---------------------------------------------------------------------------
# OpenLiteSpeed Configuration
# ---------------------------------------------------------------------------
COPY litespeed/config/httpd_config.conf /usr/local/lsws/conf/httpd_config.conf
COPY litespeed/config/vhosts/bagisto.conf /usr/local/lsws/conf/vhosts/bagisto.conf
RUN chown -R www-data:www-data /usr/local/lsws/conf /usr/local/lsws/logs


# ---------------------------------------------------------------------------
# Supervisor Configuration
# ---------------------------------------------------------------------------
COPY litespeed/config/supervisord.conf /etc/supervisor/conf.d/bagisto.conf
RUN sed -i "s/__LSPHP_VERSION__/${LSPHP_VERSION}/g" /etc/supervisor/conf.d/bagisto.conf
COPY shared/db/${DB_ENGINE}/supervisord.conf /etc/supervisor/conf.d/database.conf
COPY shared/workers/supervisord.conf /etc/supervisor/conf.d/workers.conf
COPY shared/workers/job-log.sh /usr/local/bin/bagisto-job-log
RUN chmod +x /usr/local/bin/bagisto-job-log


# ---------------------------------------------------------------------------
# Scheduler
#
# Cron runs Laravel's scheduler every minute from /etc/cron.d/bagisto, which
# it reads only while nobody but root can write to it. The jobs the packages
# drop in are removed: none ran before the image had cron, and logrotate's,
# on the PostgreSQL images, cannot make the web server reopen its logs.
# The pam_loginuid module is made optional because a container cannot change
# a login UID the host has set, and cron would then skip every job without a
# word.
# ---------------------------------------------------------------------------
COPY shared/workers/crontab /etc/cron.d/bagisto
RUN chmod 0644 /etc/cron.d/bagisto \
    && find /etc/cron.d /etc/cron.hourly /etc/cron.daily /etc/cron.weekly /etc/cron.monthly -type f ! -name bagisto ! -name .placeholder -delete \
    && sed -i 's/^session\s\+required\s\+pam_loginuid\.so/session    optional     pam_loginuid.so/' /etc/pam.d/cron


# ---------------------------------------------------------------------------
# Runtime Directories
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
