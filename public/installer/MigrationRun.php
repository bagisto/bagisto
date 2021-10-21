<?php

ini_set('max_execution_time', 900);
$phpbin = PHP_BINDIR . '/php';

// array to pass back data
$data = [];

// run command on terminal and asumming that this is as fresh installation
$command = 'cd ../.. && '. $phpbin .' artisan config:cache && '. $phpbin.' artisan migrate:fresh --force';
$data['last_line'] = exec($command, $data['migrate'], $data['results']);

// return a response
// return all our data to an AJAX call
echo json_encode($data);
