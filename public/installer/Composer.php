<?php

$data    = array(); // array to pass back data

// run command on terminal ===========================================================

$command = 'cd ../.. ; export HOME=/root && export COMPOSER_HOME=/root && /usr/bin/composer.phar self-update; composer install 2>&1';

$last_line = exec($command, $data['composer'], $data['install']);

// return a response ===========================================================

//return all our data to an AJAX call
echo json_encode($data);