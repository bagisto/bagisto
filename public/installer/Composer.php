<?php

// array to pass back data
$data    = array();

// run command on terminal
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $command = 'cd ../.. && composer install 2>&1';
} else {
    $command = 'cd ../.. ; export HOME=/root && export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update; composer install 2>&1';
}

$last_line = exec($command, $data['composer'], $data['install']);

// return a response
//return all our data to an AJAX call

echo json_encode($data);