#!/bin/bash
# ==========================================================================
# MySQL engine driver.
#
# Sourced by build-install.sh at build time and by entrypoint.sh at runtime.
# Every engine exposes the same contract, so neither caller knows which
# database it is talking to:
#
#   db_default_port        the port to assume when DB_PORT is unset
#   db_connection          the Laravel DB_CONNECTION value
#   db_build_init          create a fresh data directory
#   db_build_start         start the server in the background
#   db_build_wait          block until it accepts connections
#   db_build_provision     create the database and its user
#   db_build_stop          shut down cleanly, leaving the data on disk
#   db_runtime_ping        return 0 when a remote server is reachable
# ==========================================================================

DB_ENGINE_NAME="MySQL"

db_default_port() { echo "3306"; }

db_connection() { echo "mysql"; }

db_build_init() {
    mkdir -p /run/mysqld
    rm -rf /var/lib/mysql
    mkdir -p /var/lib/mysql
    chown -R mysql:mysql /run/mysqld /var/lib/mysql

    mysqld --initialize-insecure --user=mysql --datadir=/var/lib/mysql
}

db_build_start() {
    mysqld --user=mysql --datadir=/var/lib/mysql &

    DB_BUILD_PID=$!
}

db_build_wait() {
    mysqladmin ping -h 127.0.0.1 --silent 2>/dev/null
}

db_build_provision() {
    mysql -h 127.0.0.1 -u root < /docker-entrypoint-initdb.d/init.sql
}

db_build_stop() {
    mysqladmin -h 127.0.0.1 -u root shutdown

    wait "$DB_BUILD_PID" 2>/dev/null || true

    chown -R mysql:mysql /var/lib/mysql
}

db_runtime_ping() {
    php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (Exception \$e) { exit(1); }" 2>/dev/null
}
