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

    'emails' => [
        'backup-codes' => [
            'greeting'            => 'You have successfully enabled Two-Factor Authentication for your admin account.',
            'description'         => 'For your security, we have generated backup codes that you can use if you lose access to your authenticator app. Each code can only be used once.',
            'codes-title'         => 'Your Backup Codes',
            'codes-subtitle'      => 'Store these codes in a safe place - each can only be used once.',
            'warning-title'       => 'Important Security Notice',
            'warning-description' => 'Keep these codes secure and do not share them with anyone. Store them offline in a safe location.',
        ],
    ],

    'messages' => [
        'enabled_success'  => 'Two-Factor Authentication enabled successfully.',
        'invalid_code'     => 'Invalid verification code.',
        'disabled_success' => 'Two-Factor Authentication has been disabled.',
        'verified_success' => 'Two-Factor Authentication verified successfully.',
        'email_failed'     => 'Failed to deliver backup codes',
    ],

    'setup' => [
        'title'            => 'Enable Two-Factor Authentication',
        'scan_qr'          => 'Scan this QR code in your Google Authenticator app, then enter the 6-digit code below.',
        'code_label'       => 'Verification Code',
        'code_placeholder' => 'Enter 6-digit code',
        'back'             => 'Back',
        'verify_enable'    => 'Verify & Enable',
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
