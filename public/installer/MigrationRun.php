<?php

ini_set('max_execution_time', 300);

// array to pass back data
$data = [];

// run command on terminal and asumming that this is as fresh installation
$command = 'cd ../.. && php artisan config:cache && php artisan migrate:fresh --force';
$data['last_line'] = exec($command, $data['migrate'], $data['results']);

// return a response
// return all our data to an AJAX call
echo json_encode($data);
