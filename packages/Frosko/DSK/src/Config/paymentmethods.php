<?php

/**
 * 'dsk' => [
 * 'username' => env('DSK_USERNAME'),
 * 'password' => env('DSK_PASSWORD'),
 * 'return_url' => env('DSK_RETURN_URL'),
 * 'test_mode' => env('DSK_TEST_MODE', true),
 * ],
 */
return [
    'dsk'  => [
        'code'             => 'dsk',
        'title'            => 'DSK Bank',
        'description'      => 'DSK Bank payments',
        'class'            => 'Frosko\DSK\Payment\DSK',
        'sandbox'          => true,
        'active'           => true,
        'sort'             => 5,

    ],
];
