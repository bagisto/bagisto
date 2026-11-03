#!/bin/bash
# ===========================================================================
# postgres-server.sh — Runs the bundled PostgreSQL server in the foreground
# for Supervisor.
#
# Supervisor needs a process that does not daemonise, so `postgres` is exec'd
# directly rather than going through pg_ctlcluster. The cluster version is
# resolved here so the Supervisor config does not have to name it.
# ===========================================================================
set -e

PG_VERSION="$(ls /usr/lib/postgresql | sort -V | tail -1)"

exec "/usr/lib/postgresql/${PG_VERSION}/bin/postgres" \
    -D "/var/lib/postgresql/${PG_VERSION}/main" \
    -c "config_file=/etc/postgresql/${PG_VERSION}/main/postgresql.conf"
