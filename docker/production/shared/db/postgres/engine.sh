#!/bin/bash
# ==========================================================================
# PostgreSQL engine driver.
#
# Implements the same contract as the MySQL driver beside it — see that file
# for what each function is expected to do.
#
# The cluster version is discovered rather than pinned, so an Ubuntu base
# image that moves from PostgreSQL 16 to 17 needs no change here.
# ==========================================================================

DB_ENGINE_NAME="PostgreSQL"

pg_version() {
    ls /usr/lib/postgresql | sort -V | tail -1
}

pg_data_dir() {
    echo "/var/lib/postgresql/$(pg_version)/main"
}

db_default_port() { echo "5432"; }

db_connection() { echo "pgsql"; }

db_server_packages() { echo "postgresql postgresql-client"; }

db_build_init() {
    local version data

    version="$(pg_version)"
    data="$(pg_data_dir)"

    pg_dropcluster --stop "$version" main 2>/dev/null || true
    pg_createcluster --locale=C.UTF-8 "$version" main --start-conf=manual

    # Listen on loopback only; the container publishes HTTP, not SQL.
    echo "listen_addresses = '127.0.0.1'" >> "/etc/postgresql/${version}/main/conf.d/bagisto.conf" 2>/dev/null \
        || echo "listen_addresses = '127.0.0.1'" >> "/etc/postgresql/${version}/main/postgresql.conf"

    chown -R postgres:postgres "$data"
}

db_build_start() {
    pg_ctlcluster "$(pg_version)" main start
}

db_build_wait() {
    su postgres -c "pg_isready -h 127.0.0.1 -q" 2>/dev/null
}

db_build_provision() {
    su postgres -c "psql -v ON_ERROR_STOP=1 -f /docker-entrypoint-initdb.d/init.sql"
}

db_build_stop() {
    pg_ctlcluster "$(pg_version)" main stop -m fast

    chown -R postgres:postgres "$(pg_data_dir)"
}

db_runtime_ping() {
    php -r "try { new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (Exception \$e) { exit(1); }" 2>/dev/null
}
