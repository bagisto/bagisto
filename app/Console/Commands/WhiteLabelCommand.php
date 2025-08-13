<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Added for the .env helper
use Intervention\Image\Facades\Image;
use Symfony\Component\Process\Process;

class WhiteLabelCommand extends Command
{
    protected $signature = 'custom:white-label';

    protected $description = 'Full white-label: DB branding, logo swap from .env URLs, asset rebuild, and self-cleanup.';

    private ?string $company;
    private ?string $phone;
    private ?string $email;
    private ?string  $keywords;

    public function handle(): int
    {
        $this->bootstrapConfig();

        $logoUrl = config('app.app_logo_url');
        if (blank($logoUrl)) {
            $this->info('APP_LOGO_URL is empty — nothing to do.');
            return self::SUCCESS;
        }
        
        $this->info("=== White-labelling to “{$this->company}” ===");

        $this->refreshStorageSymlink();

        $tempLogoPath = null;
        $shouldRebuildAssets = false;

        try {
            // ... (The main try block remains the same)
            $this->line("→ Downloading single logo from APP_LOGO_URL...");
            $tempLogoPath = $this->fetchLogoFromUrl($logoUrl);

            if (is_null($tempLogoPath)) {
                return self::FAILURE;
            }

            $shouldRebuildAssets = true;
            
            DB::beginTransaction();
            try {
                $this->updateDatabaseBranding();
                $this->replaceLogoFile($tempLogoPath);
                $this->updateCopyrightText();
                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("❌  {$e->getMessage()}");
                return self::FAILURE;
            }

            if ($shouldRebuildAssets) {
                if (!$this->rebuildAssets()) {
                    return self::FAILURE;
                }
            }
        } finally {
            if ($tempLogoPath && File::exists($tempLogoPath)) {
                File::delete($tempLogoPath);
            }
        }

        $this->line('→ Clearing branding variable values from .env file...');
        $keysToClear = [
            'APP_LOGO_URL',
            'APP_COMPANY_NAME',
            'APP_CONTACT_PHONE',
            'APP_CONTACT_EMAIL',
            'APP_BRAND_KEYWORDS',
        ];

        foreach ($keysToClear as $key) {
            $this->envSet($key, ''); // Calls envSet with an empty string
        }
        
        $this->line('→ Clearing all application caches...');
        $this->clearCaches();
        
        $this->info('🎉  White-labelling finished!');
        return self::SUCCESS;
    }

    private function fetchLogoFromUrl(string $url): ?string
    {
        try {
            $response = Http::get($url);
            if (!$response->successful()) {
                $this->error("Failed to download logo. HTTP Status: {$response->status()}");
                return null;
            }
            $filename = 'temp/' . uniqid('logo_') . '_' . basename($url);
            Storage::disk('local')->put($filename, $response->body());
            $this->info("✓ Logo downloaded successfully.");
            return Storage::disk('local')->path($filename);
        } catch (\Throwable $e) {
            $this->error("Failed to download or save logo: {$e->getMessage()}");
            return null;
        }
    }

    private function replaceLogoFile(?string $tempLogoPath): void
    {
        $this->info('→ Handling logo file...');

        if (!$tempLogoPath || !File::exists($tempLogoPath)) {
            $this->line('<fg=yellow>• No logo file provided, skipping database update for logos.</>');
            return;
        }

        $channel = core()->getDefaultChannel();
        $finalPath = 'branding/logo.' . File::extension($tempLogoPath);
        $absoluteFinalPath = Storage::disk('public')->path($finalPath);

        File::ensureDirectoryExists(dirname($absoluteFinalPath));

        $this->line("→ Resizing logo to max height 80px...");
        Image::make($tempLogoPath)
            ->resize(null, 80, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($absoluteFinalPath);
        $this->line("<fg=green>• Resized logo saved to: storage/app/public/{$finalPath}</>");

        DB::table('channels')->where('id', $channel->id)->update([
            'logo' => $finalPath,
            'favicon' => $finalPath,
        ]);
        $this->line("<fg=green>• Storefront logo/favicon database entries updated.</>");

        DB::table('core_config')->updateOrInsert(
            [
                'code' => 'general.design.admin_logo.logo_image',
                'channel_code' => $channel->code,
                'locale_code' => core()->getDefaultLocaleCodeFromDefaultChannel()
            ],
            ['value' => $finalPath]
        );
        $this->line("<fg=green>• Admin logo database entry updated.</>");
    }

    private function bootstrapConfig(): void
    {
        $this->company  = config('app.company_name');
        if (!$this->company) {
            $this->error('APP_COMPANY_NAME not set in .env — aborting.');
            exit(self::FAILURE);
        }
        $this->phone    = config('app.contact_phone');
        $this->email    = config('app.contact_email');
        // REMOVED: Tagline is no longer used.
        $this->keywords = config('app.brand_keywords', "{$this->company},shop,e-commerce");
    }

    private function updateDatabaseBranding(): void
    {
        $this->info('→ Updating database branding…');
        $channel = core()->getDefaultChannel();
        $locale  = core()->getDefaultLocaleCodeFromDefaultChannel();
        if (Schema::hasTable('channel_translations')) {
            $channelRepository = resolve(\Webkul\Core\Repositories\ChannelRepository::class);
            $localeIds          = $channel->locales->pluck('id')->toArray();
            $currencyIds        = $channel->currencies->pluck('id')->toArray();
            $inventorySourceIds = $channel->inventory_sources->pluck('id')->toArray();

            $data = [
                'locales'           => $localeIds,
                'currencies'        => $currencyIds,
                'inventory_sources' => $inventorySourceIds,
                $locale             => [
                    'name'     => $this->company,
                    'home_seo' => [
                        'meta_title'       => $this->company,
                        'meta_keywords'    => $this->keywords,
                        'meta_description' => "Welcome to {$this->company}" // Add this line back
                    ],
                ],
            ];
            $channelRepository->update($data, $channel->id);
            $this->line('<fg=green>• channel_translations updated.</>');
        }
        if (Schema::hasTable('core_config')) {
            $upsert = fn ($code, $val) =>
                DB::table('core_config')->updateOrInsert(
                    ['code' => $code, 'channel_code' => $channel->code, 'locale_code' => $locale],
                    ['value' => $val]
                );
            $this->phone  && $upsert('sales.shipping.origin.contact', $this->phone);
            $this->email  && $upsert('sales.shipping.origin.email',   $this->email);
            $upsert('general.content.shop.name', $this->company);
            $this->line('<fg=green>• core_config contact/footer updated.</>');
        }
    }

    private function updateCopyrightText(): void
    {
        $this->info('→ Updating copyright text…');
        $lang = base_path('packages/Webkul/Shop/src/Resources/lang/en/app.php');
        if (!File::exists($lang)) return;
        $year = date('Y');
        $text = "© Copyright {$year}, {$this->company}. All rights reserved.";
        $data = File::get($lang);
        $data = preg_replace("/(['\"]copyright['\"]\s*=>\s*)['\"][^'\"]*['\"]/u", "$1'{$text}'", $data);
        File::put($lang, $data);
        $this->line('<fg=green>• copyright string updated.</>');
    }

    private function rebuildAssets(): bool
    {
        $this->info('→ Building assets (npm run build)…');
        $run = function (array $cmd) {
            return (new Process($cmd, base_path()))->setTimeout(360)->run() === 0;
        };
        if (!$run(['npm', 'install']))   return $this->failBuild('npm install failed');
        if (!$run(['npm', 'run', 'build'])) return $this->failBuild('npm run build failed');
        $this->line('<fg=green>• Assets built successfully.</>');
        return true;
    }

    private function failBuild(string $msg): bool
    {
        $this->error("❌  {$msg}");
        return false;
    }

    /* ------------------------------------------------------------------ */
    /* NEW HELPER METHODS FROM EXAMPLE                                    */
    /* ------------------------------------------------------------------ */

    private function envSet(string $key, string $value): void
    {
        $path = app()->environmentFilePath();
        $env = file_get_contents($path);

        // Wrap value in quotes if it contains spaces
        if (preg_match('/\s/', $value)) {
            $value = '"' . $value . '"';
        }
        
        $line = "{$key}={$value}";

        $env = Str::contains($env, "{$key}=")
            ? preg_replace("/^{$key}=.*$/m", $line, $env)
            : $env . PHP_EOL . $line . PHP_EOL;

        file_put_contents($path, $env);
    }

    private function clearCaches(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
    }

    private function refreshStorageSymlink(): void
    {
        $link = public_path('storage');

        if (is_link($link) || file_exists($link)) {
            @unlink($link);
        }
        Artisan::call('storage:link');
        $this->info('✓ Refreshed public/storage symlink.');
    }

    private function envRemove(string $key): void
    {
        $path = app()->environmentFilePath();
        $env = file_get_contents($path);

        // Regex to find the entire line for the key and remove it
        $newEnv = preg_replace("/^{$key}=.*\s*\R?/m", '', $env);

        file_put_contents($path, $newEnv);
    }
}