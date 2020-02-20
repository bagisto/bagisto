#!/bin/bash

printf "### start preparation ###\n"

    WORKPATH=$(dirname ${0})
    printf ">> workpath is %s\n" ${WORKPATH}

    LOG_DIR="${WORKPATH}/../storage/logs/tests"
    printf ">> log-dir is %s\n" ${LOG_DIR}

    printf ">> create and truncate log dir\n"
    mkdir -p ${LOG_DIR}
    rm -rf ${LOG_DIR}/*

    printf ">> truncate and migrate database\n"
    php artisan migrate:fresh --env=testing --quiet

    printf ">> cleaning laravel caches ###\n"
    ${WORKPATH}/../php artisan view:clear
    ${WORKPATH}/../php artisan route:clear
    ${WORKPATH}/../php artisan cache:clear
    ${WORKPATH}/../php artisan config:clear

    printf ">> cleaning previous tests ###\n"
    ${WORKPATH}/../vendor/bin/codecept clean
printf "### finish preparation ###\n"

printf "### start tests ###\n"

    SUCCESS=1
    execSuite() {
        ${WORKPATH}/../vendor/bin/codecept run ${1} \
     	--xml report_${1}.xml ${CODECEPT_OPTIONS} | tee ${LOG_DIR}/tests_${1}.log
        if [[ ${PIPESTATUS[0]} -ne 0 ]]
        then
            SUCCESS=0
        fi
    }

    execSuite unit
    execSuite functional

    if [[ ${?} -ne 0 ]]
    then
        SUCCESS=0
    fi

printf "### finish tests ###\n"

if [[ ${SUCCESS} -eq 1 ]]
then
    printf ">> all tests are \e[01;32mgreen\e[0m\n"
    exit 0
else
    printf ">> at least one test is \e[01;31mred\e[0m\n"
    exit 1
fi
