<?php

$data    = array(); // array to pass back data

// run command on terminal ===========================================================

    // composer home for run
    putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');

    $command = 'cd ../.. && composer install 2>&1';

    $last_line = exec($command, $data['composer'], $data['install']);

// return a response ===========================================================

//return all our data to an AJAX call
echo json_encode($data);