<?php

namespace Webkul\Installer\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;
use Webkul\Installer\Database\Seeders\ProductTableSeeder;
use Webkul\Product\Console\Commands\Indexer;

class DatabaseManager
{
    /**
     * Admin User ID for which the admin user will be created during installation.
     *
     * @var int
     */
    const USER_ID = 1;

    /**
     * Check if the application is installed.
     */
    public function isInstalled(): bool
    {
        if (! file_exists(base_path('.env'))) {
            return false;
        }

        try {
            DB::connection()->getPDO();

            $isConnected = (bool) DB::connection()->getDatabaseName();

            if (! $isConnected) {
                return false;
            }

            $hasTable = Schema::hasTable('admins');

            if (! $hasTable) {
                return false;
            }

            $userCount = DB::table('admins')->count();

            if (! $userCount) {
                return false;
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check database connection.
     */
    public function checkDatabaseConnection(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Drop all the tables and migrate in the database.
     */
    public function migrateFresh(): bool
    {
        try {
            Artisan::call('migrate:fresh');

            return true;
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Seed the database.
     */
    public function seed($data): bool
    {
        $data['parameter'] = [
            'default_locale' => $data['parameter']['default_locales'],
            'allowed_locales' => $data['parameter']['allowed_locales'],
            'default_currency' => $data['parameter']['default_currency'],
            'allowed_currencies' => $data['parameter']['allowed_currencies'],
            'skip_admin_creation' => $data['parameter']['skip_admin_creation'],
        ];

        try {
            app(BagistoDatabaseSeeder::class)->run($data['parameter']);

            return true;
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Generate fake product data.
     *
     * @return void|string
     */
    public function seedSampleProducts($parameters): bool
    {
        try {
            app(ProductTableSeeder::class)->run($parameters);

            Artisan::registerCommand(app(Indexer::class));

            Artisan::call('indexer:index', [
                '--mode' => ['full'],
                '--quiet' => true,
            ]);

            return true;
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Create admin user.
     */
    public function createAdminUser($data): bool
    {
        try {
            DB::table('admins')->insert([
                'id' => self::USER_ID,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return true;
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }
}
