<?php

return [
    /*
   |--------------------------------------------------------------------------
   | User accesses
   |--------------------------------------------------------------------------
   |
   | This file contains the connection settings with your custom user.
   |
   |
   | !!!!! ATTENTION !!!!!
   |
   | These user credentials ARE NOT your server user credentials.
   | You can type here everything you want.
   | This method of custom login is a small addition in the protection.
   | Anyway you can disable it.
   |
   |
   |
   | The preferred type of editing the accesses is to edit your .env file
   | Kindly add the following lines in your .env file
   |          CONSOLE_USER_NAME={name}
   |          CONSOLE_USER_PASSWORD={password}
   |
   */

    // Disable login (don't ask for credentials, be careful)
    'no_login' => false,

    // Single-user credentials (REQUIRED)
    'user' => [
        'name' => env('CONSOLE_USER_NAME', 'root'),
        'password' => env('CONSOLE_USER_PASSWORD', 'root'),
    ],

    // Multi-user credentials (OPTIONAL)
    // Example: 'user' => 'password', 'user1' => 'password1'
    'accounts' => [
    //  'user' => 'password',
    ],

    // Hash incoming password
    // By default it's sha256
    'password_hash_algorithm' => '',

    // Home directory (multi-user mode supported)
    // Example: 'home_dir' => '/tmp';
    //          'home_dir' => array('user1' => '/home/user1', 'user2' => '/home/user2');
    'home_dir' => '',
];
