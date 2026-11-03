#!/bin/bash
# ===========================================================================
# job-log.sh — Files a background job's output by day, installed as
# /usr/local/bin/bagisto-job-log.
#
#   <command> 2>&1 | bagisto-job-log <job>
#
# Each line read is appended to /var/log/bagisto/<job>/YYYY-MM-DD.log and
# echoed to stdout, so the line still reaches `docker logs` wherever the
# caller sends stdout there. The date is the store's, from APP_TIMEZONE in
# .env, so a line lands in the file of the date Laravel printed on it. When
# a new day's file opens, files older than JOB_LOG_DAYS days are removed, and
# a value of 0 keeps every file.
# ===========================================================================
set -u

JOB="${1:?usage: bagisto-job-log <job>}"
DIR="/var/log/bagisto/${JOB}"
DAYS="${JOB_LOG_DAYS:-14}"

APP_TIMEZONE="$(sed -n 's/^APP_TIMEZONE=//p' /var/www/bagisto/.env 2>/dev/null | tr -d "\"'")"

[ -n "$APP_TIMEZONE" ] && export TZ="$APP_TIMEZONE"

mkdir -p "$DIR"

current=""

prune() {
    [[ "$DAYS" =~ ^[0-9]+$ ]] && [ "$DAYS" -gt 0 ] || return 0

    local cutoff file

    cutoff="$(date -d "${current} -$((DAYS - 1)) days" +%Y-%m-%d)"

    for file in "$DIR"/*.log; do
        [ -e "$file" ] || continue

        [[ "$(basename "$file" .log)" < "$cutoff" ]] && rm -f "$file"
    done
}

while IFS= read -r line || [ -n "$line" ]; do
    [ -z "${line//[[:space:]]/}" ] && continue

    printf -v today '%(%Y-%m-%d)T' -1

    if [ "$today" != "$current" ]; then
        current="$today"

        exec 3>>"${DIR}/${current}.log"

        prune
    fi

    printf '%s\n' "$line" >&3
    printf '%s\n' "$line"
done
