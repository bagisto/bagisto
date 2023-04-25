#!/bin/bash

output_message() {
  printf "%s\n" "$1"
}

output_message "### start preparation ###"

    WORKPATH=$(dirname ${0})
    output_message ">> workpath is %s" ${WORKPATH}

    LOG_DIR="${WORKPATH}/../storage/logs/tests"
    output_message ">> log-dir is %s" ${LOG_DIR}

    output_message ">> create and truncate log dir"
    mkdir -p ${LOG_DIR}
    rm -rf ${LOG_DIR}/*

    output_message ">> truncate and migrate database"
    php artisan migrate:fresh --env=testing --quiet

    output_message ">> cleaning previous tests ###"
    ${WORKPATH}/../vendor/bin/codecept clean
output_message "### finish preparation ###"

output_message "### start tests ###"

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

output_message "### finish tests ###"

if [[ ${SUCCESS} -eq 1 ]]
then
    output_message ">> all tests are \e[01;32mgreen\e[0m"
    exit 0
else
    output_message ">> at least one test is \e[01;31mred\e[0m"
    exit 1
fi
