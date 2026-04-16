<?php

namespace Webkul\Core\Enums;

enum SupportedDatabaseEnum: string
{
    /**
     * MySQL.
     */
    case MYSQL = 'mysql';

    /**
     * MariaDB.
     */
    case MARIADB = 'mariadb';

    /**
     * PostgreSQL.
     */
    case PGSQL = 'pgsql';

    /**
     * Default port for the database driver.
     */
    public function defaultPort(): string
    {
        return match ($this) {
            self::MYSQL, self::MARIADB => '3306',
            self::PGSQL => '5432',
        };
    }

    /**
     * Check if the given driver name is PostgreSQL.
     */
    public static function isPostgres(?string $driver = null): bool
    {
        return ($driver ?? self::currentDriver()) === self::PGSQL->value;
    }

    /**
     * Check if the given driver name is MySQL or MariaDB.
     */
    public static function isMysql(?string $driver = null): bool
    {
        return in_array($driver ?? self::currentDriver(), [self::MYSQL->value, self::MARIADB->value]);
    }

    /**
     * Get the current database driver name.
     */
    public static function currentDriver(): string
    {
        return app('db')->getDriverName();
    }

    /**
     * Options for installer select fields.
     */
    public static function options(): array
    {
        return [
            self::MYSQL->value,
            self::PGSQL->value,
        ];
    }
}
