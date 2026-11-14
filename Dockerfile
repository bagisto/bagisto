# syntax=docker/dockerfile:1.7

# ===========================================================================
# Bagisto application image — PHP-FPM + source + vendor.
#
# One image serves every runtime role: the web container, the queue worker and
# the scheduler all use it and only change their command. It contains no
# database, no web server and no installation step; configuration arrives at
# runtime through environment variables.
# ===========================================================================

ARG PHP_VERSION=8.4.27

# ---------------------------------------------------------------------------
# Base: PHP-FPM plus the extensions Bagisto actually needs.
#
# Extension provenance — only extensions absent from php:8.4-fpm are installed:
#   calendar  composer.json "ext-calendar" + Installer ServerRequirements.php
#   intl      composer.json "ext-intl" + Installer ServerRequirements.php
#   pdo_mysql composer.json "ext-pdo_mysql"
#   gd        Installer ServerRequirements.php + composer.lock (mpdf,
#             phpoffice/phpspreadsheet, simplesoftwareio/simple-qrcode)
#   bcmath    composer.lock (moneyphp/money)
#   zip       composer.lock (phpoffice/phpspreadsheet) + ZipArchive in
#             Admin/src/Http/Controllers/Settings/DataTransfer/ImportController
#
# Already compiled into php:8.4-fpm (verified with `php -m`), so not installed:
#   ctype, curl, dom, fileinfo, filter, hash, iconv, json, libxml, mbstring,
#   openssl, pcre, pdo, session, simplexml, tokenizer, xml, xmlreader,
#   xmlwriter, zlib.
#
# Deliberately not installed, no mandatory evidence:
#   imagick (optional IMAGE_DRIVER=imagick), redis (predis is used),
#   pdo_pgsql (only for a PostgreSQL deployment), gmp, soap, sockets, pcntl,
#   exif.
# ---------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-bookworm AS base

USER root

# TODO: pin mlocati/php-extension-installer by digest.
COPY --from=mlocati/php-extension-installer:2.13.2 /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        calendar \
        intl \
        pdo_mysql \
        gd \
        bcmath \
        zip \
    && rm -f /usr/local/bin/install-php-extensions

# php-fpm: inherit the container environment and keep every log on stderr.
# The official image already does this in docker.conf; declared here explicitly
# so the contract survives a base-image update.
COPY <<"EOF" /usr/local/etc/php-fpm.d/zz-bagisto.conf
[www]
clear_env = no
catch_workers_output = yes
access.log = /proc/self/fd/2
EOF

WORKDIR /var/www/html

# ---------------------------------------------------------------------------
# Vendor: dependencies installed before the source is copied, so editing source
# reuses this layer. Composer and unzip stay in this stage, not in the image.
# ---------------------------------------------------------------------------
FROM base AS vendor

# TODO: pin composer by digest.
COPY --from=composer:2.11.2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY composer.json composer.lock ./

# --no-scripts: composer.json's "post-autoload-dump" runs package:discover,
# which needs the application source. It runs in the final stage instead.
# check-platform-reqs fails the build if an extension is missing.
RUN --mount=type=cache,target=/tmp/composer-cache \
    COMPOSER_CACHE_DIR=/tmp/composer-cache \
    composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
    && composer check-platform-reqs --lock --no-dev

# ---------------------------------------------------------------------------
# Final: application source + vendor, running unprivileged as www-data.
# ---------------------------------------------------------------------------
FROM base AS final

ARG VCS_REF=unknown
ARG BUILD_DATE=unknown

LABEL org.opencontainers.image.title="bagisto-app" \
      org.opencontainers.image.description="Bagisto monolith application (PHP-FPM, queue worker, scheduler)" \
      org.opencontainers.image.source="https://github.com/bagisto/bagisto" \
      org.opencontainers.image.revision="${VCS_REF}" \
      org.opencontainers.image.created="${BUILD_DATE}" \
      org.opencontainers.image.licenses="MIT"

# --chown avoids a copy-up of the whole tree (a 400MB+ layer) later.
COPY --chown=www-data:www-data --from=vendor /var/www/html/vendor ./vendor

COPY --chown=www-data:www-data . .

# package:discover replaces the composer script skipped in the vendor stage.
RUN mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# Bagisto's installer treats a missing `.env` as "not installed", and the image
# ships no `.env` on purpose. Materialise the non-secret template at start so a
# restart after installation does not send the storefront back to /install.
# Real environment variables still win: Laravel's dotenv repository is immutable.
COPY --chmod=755 <<"EOF" /usr/local/bin/bagisto-entrypoint
#!/bin/sh
set -eu

if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

exec docker-php-entrypoint "$@"
EOF

USER www-data

EXPOSE 9000

ENTRYPOINT ["bagisto-entrypoint"]

CMD ["php-fpm"]
