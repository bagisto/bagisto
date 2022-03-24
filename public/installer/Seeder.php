<?php

/* max execution time limit */
ini_set('max_execution_time', 900);

/* php bin path */
$phpbin = PHP_BINDIR . '/php';

/* commands sequence */
$commands = [
    'seeder'       => [
        'key'     => 'seeder_results',
        'command' => 'cd ../.. && '. $phpbin .' artisan db:seed 2>&1'
    ],
    'publish'      => [
        'key'     => 'publish_results',
        'command' => 'cd ../.. && '. $phpbin .' artisan vendor:publish --all --force 2>&1'
    ],
    'storage_link' => [
        'key'     => 'storage_link_results',
        'command' => 'cd ../.. && '. $phpbin .' artisan storage:link 2>&1'
    ],
    'key'          => [
        'key'     => 'key_results',
        'command' => 'cd ../.. && '. $phpbin .' artisan key:generate 2>&1'
    ],
    'optimize'     => [
        'key'     => 'optimize_results',
        'command' => 'cd ../.. && '. $phpbin .' artisan optimize 2>&1'
    ],
];

// run command on terminal
$data = [];
foreach ($commands as $key => $value) {
    exec($value['command'], $data[$key], $data[$value['key']]);
}

// return a response
// return all our data to an AJAX call
echo json_encode($data);
