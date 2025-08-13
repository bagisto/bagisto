<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class WebkulBrandingService
{
    public function updateCompanyName(string $companyName): int
    {
        if (! Schema::hasTable('channel_translations')) {
            return 0;
        }
        $updatedCount = DB::table('channel_translations')->update(['name' => $companyName]);
        Log::info("Updated 'name' in 'channel_translations' for {$updatedCount} records.", ['name' => $companyName]);
        return $updatedCount;
    }

    public function updateFrontendLogo(string $logoPath): int
    {
        if (! $this->validateLogoPath($logoPath) || ! Schema::hasTable('theme_customizations')) {
            return 0;
        }
        $updatedCount = DB::table('theme_customizations')
            ->where('type', 'logo_image')->where('status', 1)
            ->update(['options' => DB::raw("JSON_SET(options, '$.image_1', '{$logoPath}')")]);
        Log::info("Updated frontend logo for {$updatedCount} themes.", ['path' => $logoPath]);
        return $updatedCount;
    }

    public function updateAdminLogo(string $logoPath): int
    {
        if (! $this->validateLogoPath($logoPath)) {
            return 0;
        }
        return $this->updateCoreConfig('general.design.admin_logo.logo_image', $logoPath);
    }



    public function updateContactInfo(?string $phone, ?string $email): int
    {
        $updatedCount = 0;
        if ($phone) {
            $updatedCount += $this->updateCoreConfig('sales.shipping.origin.contact', $phone);
        }
        if ($email) {
            $updatedCount += $this->updateCoreConfig('sales.shipping.origin.email', $email);
        }
        return $updatedCount;
    }

    private function updateCoreConfig(string $key, string $value): int
    {
        if (! Schema::hasTable('core_config')) {
            return 0;
        }
        $affected = DB::table('core_config')->updateOrInsert(
            ['code' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
        Log::info("Updated core_config key '{$key}'.");
        return $affected ? 1 : 0;
    }

    private function validateLogoPath(string $path): bool
    {
        if (! File::exists(public_path($path))) {
            Log::error("Logo file not found at path: " . public_path($path));
            return false;
        }
        return true;
    }
}