<?php

ini_set('max_execution_time', 900);

$phpBin = PHP_BINDIR . '/php';

// array to pass back data
$data = [];

$command = 'cd ../.. && '. $phpBin .' artisan config:cache && '. $phpBin.' artisan migrate:fresh --force';

/**
 * Run migration command on terminal
 */
$data['last_line'] = exec($command, $data['migrate'], $data['results']);

/**
 * Return all our data to an AJAX call
 */
echo json_encode($data);
