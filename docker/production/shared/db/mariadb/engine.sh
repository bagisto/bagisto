#!/bin/bash
# ==========================================================================
# MariaDB engine driver.
#
# Sourced by build-install.sh at build time and by entrypoint.sh at runtime.
# It exposes the same contract every engine does, so neither caller knows
# which database it is talking to — see shared/db/mysql/engine.sh for the
# contract. MariaDB speaks the MySQL protocol, so PHP uses the same pdo_mysql
# driver and the same 3306 port; only the server binaries differ.
# ==========================================================================

DB_ENGINE_NAME="MariaDB"

db_default_port() { echo "3306"; }

db_connection() { echo "mariadb"; }

db_build_init() {
    mkdir -p /run/mysqld
    rm -rf /var/lib/mysql
    mkdir -p /var/lib/mysql
    chown -R mysql:mysql /run/mysqld /var/lib/mysql

    mariadb-install-db --user=mysql --datadir=/var/lib/mysql --auth-root-authentication-method=normal
}

db_build_start() {
    mariadbd --user=mysql --datadir=/var/lib/mysql &

    DB_BUILD_PID=$!
}

db_build_wait() {
    mariadb-admin ping -h 127.0.0.1 --silent 2>/dev/null
}

db_build_provision() {
    mariadb -h 127.0.0.1 -u root < /docker-entrypoint-initdb.d/init.sql
}

db_build_stop() {
    mariadb-admin -h 127.0.0.1 -u root shutdown

    wait "$DB_BUILD_PID" 2>/dev/null || true

    chown -R mysql:mysql /var/lib/mysql
}

db_runtime_ping() {
    php -r "try { new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (Exception \$e) { exit(1); }" 2>/dev/null
}
