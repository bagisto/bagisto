<?php

namespace Webkul\Product\Enums;

enum ElasticAuthEnum: string
{
    /**
     * Reached by host, with an open cluster that asks for nothing.
     */
    case NONE = 'none';

    /**
     * Reached by host, with a username and password.
     */
    case BASIC = 'basic';

    /**
     * Reached by host, with an API key.
     */
    case API_KEY = 'api_key';

    /**
     * Reached by Cloud ID, with an API key.
     */
    case CLOUD_API_KEY = 'cloud_api_key';

    /**
     * Reached by Cloud ID, with a username and password.
     */
    case CLOUD_BASIC = 'cloud_basic';

    /**
     * The configured connection this way of connecting is carried by.
     */
    public function connection(): string
    {
        return match ($this) {
            self::CLOUD_API_KEY, self::CLOUD_BASIC => 'cloud',
            self::API_KEY => 'api',
            self::NONE, self::BASIC => 'default',
        };
    }

    /**
     * The settings this way of connecting reads, so the rest are left alone.
     *
     * @return array<int, string>
     */
    public function settings(): array
    {
        return match ($this) {
            self::NONE => ['hosts'],
            self::BASIC => ['hosts', 'username', 'password'],
            self::API_KEY => ['hosts', 'api_key'],
            self::CLOUD_API_KEY => ['cloud_id', 'api_key'],
            self::CLOUD_BASIC => ['cloud_id', 'username', 'password'],
        };
    }

    /**
     * The ways of connecting that read a given setting.
     *
     * @return array<int, string>
     */
    public static function readers(string $setting): array
    {
        return array_values(array_map(
            fn (self $auth) => $auth->value,
            array_filter(self::cases(), fn (self $auth) => in_array($setting, $auth->settings()))
        ));
    }
}
