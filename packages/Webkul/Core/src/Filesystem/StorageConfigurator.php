<?php

namespace Webkul\Core\Filesystem;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Enums\SupportedFilesystemEnum;

class StorageConfigurator
{
    /**
     * Where each recorded setting belongs in the filesystem configuration.
     */
    protected const S3_SETTINGS = [
        'file_management.amazon_s3.settings.key' => 'key',
        'file_management.amazon_s3.settings.secret' => 'secret',
        'file_management.amazon_s3.settings.region' => 'region',
        'file_management.amazon_s3.settings.bucket' => 'bucket',
        'file_management.amazon_s3.settings.url' => 'url',
        'file_management.amazon_s3.settings.endpoint' => 'endpoint',
        'file_management.amazon_s3.settings.use_path_style_endpoint' => 'use_path_style_endpoint',
    ];

    /**
     * Where each recorded Cloudflare R2 setting belongs on its disk.
     */
    protected const R2_SETTINGS = [
        'file_management.cloudflare_r2.settings.key' => 'key',
        'file_management.cloudflare_r2.settings.secret' => 'secret',
        'file_management.cloudflare_r2.settings.bucket' => 'bucket',
        'file_management.cloudflare_r2.settings.url' => 'url',
    ];

    /**
     * The endpoint an R2 account is reached at.
     */
    protected const R2_ENDPOINT = 'https://%s.r2.cloudflarestorage.com';

    /**
     * Point the application at the disk the admin chose, never leaving it on a remote disk
     * that is missing the settings it needs.
     */
    public function configure(): void
    {
        $this->applyChosenDisk();

        $this->leaveIncompleteDisk();
    }

    /**
     * Apply the disk the admin chose, read straight from the table because the disk is
     * settled before the channel aware reader is available.
     */
    protected function applyChosenDisk(): void
    {
        $settings = $this->settings();

        if (is_null($settings)) {
            return;
        }

        $chosen = $settings[SupportedFilesystemEnum::CONFIG_KEY] ?? null;

        if (empty($chosen)) {
            return;
        }

        $driver = SupportedFilesystemEnum::fromConfig($chosen);

        if (! $driver->isAvailable()) {
            return;
        }

        match ($driver) {
            SupportedFilesystemEnum::S3 => $this->configureS3($settings),
            SupportedFilesystemEnum::R2 => $this->configureR2($settings),
            default => null,
        };

        if (! $this->isConfigured($driver)) {
            return;
        }

        config(['filesystems.default' => $driver->value]);
    }

    /**
     * Apply the recorded credentials to the s3 disk, passing over any left empty so the
     * environment stays in charge of them.
     */
    protected function configureS3(array $settings): void
    {
        foreach (self::S3_SETTINGS as $code => $key) {
            $value = $settings[$code] ?? null;

            if (
                is_null($value)
                || $value === ''
            ) {
                continue;
            }

            config([
                'filesystems.disks.s3.'.$key => $key === 'use_path_style_endpoint'
                    ? filter_var($value, FILTER_VALIDATE_BOOLEAN)
                    : $value,
            ]);
        }
    }

    /**
     * Apply the recorded credentials to the Cloudflare R2 disk, which builds its endpoint
     * from the account and always uses the "auto" region.
     */
    protected function configureR2(array $settings): void
    {
        foreach (self::R2_SETTINGS as $code => $key) {
            $value = $settings[$code] ?? null;

            if (
                is_null($value)
                || $value === ''
            ) {
                continue;
            }

            config(['filesystems.disks.r2.'.$key => $value]);
        }

        $account = $settings['file_management.cloudflare_r2.settings.account_id'] ?? null;

        if (! empty($account)) {
            config(['filesystems.disks.r2.endpoint' => sprintf(self::R2_ENDPOINT, $account)]);
        }

        config([
            'filesystems.disks.r2.region' => 'auto',
            'filesystems.disks.r2.use_path_style_endpoint' => true,
        ]);
    }

    /**
     * Whether the resolved disk carries every setting it needs to be reached, so
     * an incomplete remote disk is left unselected instead of breaking the panel.
     */
    protected function isConfigured(SupportedFilesystemEnum $driver): bool
    {
        foreach ($driver->requiredDiskSettings() as $setting) {
            if (empty(config('filesystems.disks.'.$driver->value.'.'.$setting))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Fall back to the local disk when the disk in force is a remote one missing settings,
     * whether the admin or the environment named it.
     */
    protected function leaveIncompleteDisk(): void
    {
        $driver = SupportedFilesystemEnum::tryFrom((string) config('filesystems.default'));

        if (
            is_null($driver)
            || $this->isConfigured($driver)
        ) {
            return;
        }

        config(['filesystems.default' => SupportedFilesystemEnum::default()->value]);
    }

    /**
     * Every file management setting the store has recorded, or null when they
     * cannot be read at all.
     *
     * @return array<string, string|null>|null
     */
    protected function settings(): ?array
    {
        try {
            return DB::table('core_config')
                ->where('code', 'like', SupportedFilesystemEnum::CONFIG_PREFIX.'%')
                ->pluck('value', 'code')
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }
}
