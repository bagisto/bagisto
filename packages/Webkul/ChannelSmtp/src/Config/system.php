<?php

return [
    [
        'key' => 'general.smtp',
        'name' => 'channelsmtp::app.configuration.general.smtp.title',
        'info' => 'channelsmtp::app.configuration.general.smtp.info',
        'icon' => 'settings/email.svg',
        'sort' => 99,
    ], [
        'key' => 'general.smtp.settings',
        'name' => 'channelsmtp::app.configuration.general.smtp.settings.title',
        'info' => 'channelsmtp::app.configuration.general.smtp.settings.info',
        'sort' => 1,
        'fields' => [
            [
                'name' => 'enabled',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.enable',
                'type' => 'boolean',
                'channel_based' => true,
                'default' => false,
            ], [
                'name' => 'host',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.host',
                'type' => 'text',
                'channel_based' => true,
                'validation' => 'required_if:general.smtp.settings.enabled,1',
            ], [
                'name' => 'port',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.port',
                'type' => 'number',
                'channel_based' => true,
                'validation' => 'required_if:general.smtp.settings.enabled,1|integer|min:1',
                'default' => 587,
            ], [
                'name' => 'encryption',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.encryption',
                'type' => 'select',
                'channel_based' => true,
                'default' => 'tls',
                'options' => [
                    [
                        'title' => 'channelsmtp::app.configuration.general.smtp.settings.encryption-none',
                        'value' => 'none',
                    ], [
                        'title' => 'TLS',
                        'value' => 'tls',
                    ], [
                        'title' => 'SSL',
                        'value' => 'ssl',
                    ],
                ],
            ], [
                'name' => 'username',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.username',
                'type' => 'text',
                'channel_based' => true,
            ], [
                'name' => 'password',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.password',
                'type' => 'password',
                'channel_based' => true,
            ], [
                'name' => 'timeout',
                'title' => 'channelsmtp::app.configuration.general.smtp.settings.timeout',
                'type' => 'number',
                'channel_based' => true,
                'validation' => 'integer|min:1',
                'default' => 30,
            ],
        ],
    ],
];
