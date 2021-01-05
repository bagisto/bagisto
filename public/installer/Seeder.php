<?php

// array to pass back data
$data = array();

// run command on terminal
$key = 'cd ../.. && php artisan key:generate 2>&1';
$seeder = 'cd ../.. && php artisan db:seed 2>&1';
$publish = 'cd ../.. && php artisan vendor:publish --all --force 2>&1';

$key_output = exec($key, $data['key'], $data['key_results']);
$seeder_output = exec($seeder, $data['seeder'], $data['seeder_results']);
$publish = exec($publish, $data['publish'], $data['publish_results']);

// return a response
//return all our data to an AJAX call

echo json_encode($data);