<?php

/* max execution time limit */
ini_set('max_execution_time', 900);

/* php bin path */
$phpBin = PHP_BINDIR . '/php';

/* commands sequence */
$commands = [
    'seeder'       => [
        'key'     => 'seeder_results',
        'command' => 'cd ../.. && '. $phpBin .' artisan db:seed 2>&1'
    ],

    'publish'      => [
        'key'     => 'publish_results',
        'command' => 'cd ../.. && '. $phpBin .' artisan bagisto:publish --force 2>&1'
    ],

    'storage_link' => [
        'key'     => 'storage_link_results',
        'command' => 'cd ../.. && '. $phpBin .' artisan storage:link 2>&1'
    ],

    'key'          => [
        'key'     => 'key_results',
        'command' => 'cd ../.. && '. $phpBin .' artisan key:generate 2>&1'
    ],

    'optimize'     => [
        'key'     => 'optimize_results',
        'command' => 'cd ../.. && '. $phpBin .' artisan optimize 2>&1'
    ],
];

/**
 * Run seeder command on terminal
 */
$data = [];

foreach ($commands as $key => $value) {
    exec($value['command'], $data[$key], $data[$value['key']]);
}

/**
 * Return all our data to an AJAX call
 */
echo json_encode($data);
