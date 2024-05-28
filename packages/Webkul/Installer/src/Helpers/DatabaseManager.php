<?php

namespace Webkul\Installer\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;
use Webkul\Product\Repositories\ProductRepository;

class DatabaseManager
{
    public function __construct(
        protected ProductRepository $productRepository,
    ) {
    }

    /**
     * Check Database Connection.
     */
    public function isInstalled()
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
     * Drop all the tables and migrate in the database
     *
     * @return void|string
     */
    public function migration()
    {
        try {
            Artisan::call('migrate:fresh');
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Seed the database.
     *
     * @return void|string
     */
    public function seeder($data)
    {
        try {
            app(BagistoDatabaseSeeder::class)->run($data['parameter']);

            $this->storageLink();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Storage Link.
     */
    private function storageLink()
    {
        Artisan::call('storage:link');
    }

    /**
     * Generate New Application Key
     */
    public function generateKey()
    {
        try {
            Artisan::call('key:generate');
        } catch (Exception $e) {
        }
    }

    /**
     * Generate fake product data.
     *
     * @return void|string
     */
    public function faker()
    {
        try {
            $fileTmpPath = Storage::path('data-transfer/samples/products.csv');
            $fileContent = file_get_contents($fileTmpPath);

            $lines = explode(PHP_EOL, $fileContent);

            $arrayData = [];

            if (count($lines) > 0) {
                $headers = str_getcsv(array_shift($lines));

                foreach ($lines as $line) {
                    if (! empty($line)) {
                        $row = str_getcsv($line);
                        $arrayData[] = array_combine($headers, $row);
                    }
                }
            }

            $this->productRepository->insert($arrayData);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
