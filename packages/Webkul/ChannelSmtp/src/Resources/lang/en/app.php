<?php

return [
    'configuration' => [
        'general' => [
            'smtp' => [
                'title' => 'SMTP',
                'info' => 'Configure channel-wise SMTP transport.',
                'settings' => [
                    'title' => 'SMTP Settings',
                    'info' => 'These SMTP credentials are applied for outgoing emails on the selected channel.',
                    'enable' => 'Enable SMTP',
                    'host' => 'SMTP Host',
                    'port' => 'SMTP Port',
                    'encryption' => 'Encryption',
                    'encryption-none' => 'None',
                    'username' => 'SMTP Username',
                    'password' => 'SMTP Password',
                    'timeout' => 'SMTP Timeout (seconds)',
                ],
            ],
        ],
    ],
];
