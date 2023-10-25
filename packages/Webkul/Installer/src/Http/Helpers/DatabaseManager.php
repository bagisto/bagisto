<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseManager
{
    /**
     * Check Database Connection.
     */
    public function checkConnection()
    {
        if (! file_exists(base_path('.env'))) {
            return false;
        }

        try {
            DB::connection()->getPDO();

            return (bool) DB::connection()->getDatabaseName();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Drop all the tables and migrate in the database
     *
     * @return string
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

        return $this->seeder();
    }

    /**
     * Seed the database.
     *
     * @return string
     */
    private function seeder()
    {
        try {
            Artisan::call('db:seed');

            $seederLog = Artisan::output();

            $this->storageLink();

            return $seederLog;
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
}
