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
        'verified_success' => 'Two-Factor Authentication verified successfully.',
    ],

    'setup' => [
        'title'        => 'Enable Two-Factor Authentication',
        'scan_qr'      => 'Scan this QR code in your Google Authenticator app, then enter the 6-digit code below.',
        'code_label'   => 'Verification Code',
        'code_placeholder' => 'Enter 6-digit code',
        'back'         => 'Back',
        'verify_enable'=> 'Verify & Enable',
    ],

    'verify' => [
        'title'                 => 'Verify Two-Factor Authentication',
        'enter_code'            => 'Enter the 6-digit code from your authenticator app to continue.',
        'code_label'            => 'Verification Code',
        'code_placeholder'      => 'Enter 6-digit code',
        'back'                  => 'Back',
        'verify_code'           => 'Verify Code',
        'disabled_message'      => 'Verify Two-Factor Authentication is currently disabled by the admin.',
    ],
];
