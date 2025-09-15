<?php

return [
    'configuration' => [
        'index' => [
            'general' => [
                'two_factor_auth' => [
                    'title'    => 'Two Factor Authentication',
                    'info'     => 'Manage Two-Factor Authentication settings for admin users.',

                    'settings' => [
                        'title'   => 'Settings',
                        'info'    => 'Manage Two Factor Authentication for admin users.',
                        'enabled' => 'Enable Two-Factor Authentication',
                    ],
                ],
            ],
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Two-Factor Authentication enabled successfully.',
        'invalid_code'     => 'Invalid verification code.',
        'disabled_success' => 'Two-Factor Authentication has been disabled.',
        'verified_success'  => 'Two-Factor Authentication verified successfully.',
    ],
];
