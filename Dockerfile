# syntax=docker/dockerfile:1.7
# Image app Bagisto: chỉ php-fpm + source + vendor. Không nginx, không DB.
# Build CI/K8s: docker build .            (stage cuối = runtime)
# Dev compose:  docker build --target dev .
ARG PHP_VERSION=8.4

# --- base: PHP + extension (danh sách do dev/tài liệu Bagisto cung cấp) ---
#   calendar, intl, pdo_mysql <- composer.json
#   gd                        <- trình cài đặt Bagisto + composer.lock
#   zip, bcmath               <- composer.lock
FROM php:${PHP_VERSION}-fpm-bookworm AS base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions calendar intl pdo_mysql gd zip bcmath \
    && apt-get update \
    && apt-get upgrade -y \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html



# --- tools: công cụ build, KHÔNG đi vào image cuối ---
FROM base AS tools
ENV COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_CACHE_DIR=/tmp/composer-cache
RUN apt-get update && apt-get install -y --no-install-recommends unzip \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer




# --- vendor: tải thư viện (cache theo composer.lock), rồi mới copy source ---
FROM tools AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install --no-dev --no-interaction --prefer-dist --no-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts



# --- dev: dùng cho docker-compose (source được mount từ máy) ---
FROM tools AS dev
USER www-data



# --- runtime: image cuối cho CI/K8s ---
FROM base AS runtime
ARG VCS_REF=unknown
ARG BUILD_DATE=unknown
LABEL org.opencontainers.image.revision=$VCS_REF \
      org.opencontainers.image.created=$BUILD_DATE
# Cấu hình php-fpm nhúng thẳng vào Dockerfile (không cần file ngoài):
#   catch_workers_output: log của worker ra stdout/stderr
#   clear_env=no: giữ biến môi trường (K8s/Vault truyền cấu hình qua env) cho PHP



COPY <<'EOF' /usr/local/etc/php-fpm.d/zz-bagisto.conf
[www]
listen = 9000
catch_workers_output = yes
decorate_workers_output = no
clear_env = no
EOF
COPY --from=vendor --chown=www-data:www-data /app /var/www/html
RUN cp .env.example .env \
    && sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env \
    && sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env \
    && chown www-data:www-data .env \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache
USER www-data
EXPOSE 9000