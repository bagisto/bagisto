<?php

namespace Webkul\Core\Enums;

use League\Flysystem\AwsS3V3\AwsS3V3Adapter;

enum SupportedFilesystemEnum: string
{
    /**
     * The disk the application ships with, served from the site itself.
     */
    case LOCAL = 'public';

    /**
     * Amazon S3, and anything speaking its api.
     */
    case S3 = 's3';

    /**
     * Cloudflare R2, which speaks the S3 api behind an account scoped endpoint.
     */
    case R2 = 'r2';

    /**
     * The setting the admin records the chosen disk under.
     */
    public const CONFIG_KEY = 'file_management.general.settings.default_driver';

    /**
     * The prefix every file management setting is recorded under.
     */
    public const CONFIG_PREFIX = 'file_management.';

    /**
     * The driver a store falls back to, and installs with.
     */
    public static function default(): self
    {
        return self::LOCAL;
    }

    /**
     * The disk a stored value names, or the default when it names nothing known.
     */
    public static function fromConfig(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::default();
    }

    /**
     * The disk the application is writing to right now.
     */
    public static function currentDriver(): self
    {
        return self::fromConfig(config('filesystems.default'));
    }

    /**
     * The drivers existing files can be copied onto, as the configuration screen
     * records them.
     */
    public static function syncable(): array
    {
        return array_values(array_map(
            fn (self $driver) => $driver->value,
            array_filter(self::cases(), fn (self $driver) => $driver->supportsSync())
        ));
    }

    /**
     * Where this driver's name is kept, so it reads as the configuration screen names it.
     */
    public function title(): string
    {
        return 'admin::app.configuration.index.file-management.general.settings.drivers.'.match ($this) {
            self::LOCAL => 'local',
            self::S3 => 's3',
            self::R2 => 'r2',
        };
    }

    /**
     * The adapter package a driver needs, for the ones that are not built in.
     */
    public function adapter(): ?string
    {
        return match ($this) {
            self::LOCAL => null,
            self::S3, self::R2 => AwsS3V3Adapter::class,
        };
    }

    /**
     * Whether the driver can be used, which for a remote disk means its adapter
     * is installed.
     */
    public function isAvailable(): bool
    {
        $adapter = $this->adapter();

        return is_null($adapter)
            || class_exists($adapter);
    }

    /**
     * Whether files already stored can be copied onto this driver, which only a
     * remote disk the application can reach allows.
     */
    public function supportsSync(): bool
    {
        return match ($this) {
            self::LOCAL => false,
            self::S3, self::R2 => $this->isAvailable(),
        };
    }
}
