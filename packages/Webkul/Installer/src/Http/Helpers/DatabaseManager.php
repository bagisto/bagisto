<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DatabaseManager
{
    /**
     * Check Database Connection.
     *
     * @param array $params
     * @return 
     */
    public function getEnvironment($params = [])
    {

        if (! file_exists(base_path('.env'))) {
            return false;
        }

        $defaultParams = [
            'DB_HOST'       => env('DB_HOST'),
            'DB_DATABASE'   => env('DB_DATABASE'),
            'DB_USERNAME'   => env('DB_USERNAME'),
            'DB_PASSWORD'   => env('DB_PASSWORD'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_PORT'       => env('DB_PORT'),
            'DB_PREFIX'     => env('DB_PREFIX'),
        ];

        try {
            $pdo = DB::connection()->getPdo();

            if ($pdo) {
                return true;
            } else {
                $database = DB::connection()->getDatabaseName();

                if (! file_exists($database)) {
                    touch($database);

                    DB::reconnect(Config::get('database.default'));

                    return $defaultParams;
                }
            }
        } catch (Exception $e) {
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
            Artisan::call('migrate:fresh', ['--force'=> true]);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->generateKey();
    }

    /**
     * Generate New Application Key
     *
     * @return string
     */
    private function generateKey()
    {
        try {
            if (config('installer.final.key')) {
                Artisan::call('key:generate', ['--force'=> true]);
            }
        } catch (Exception $e) {
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
            Artisan::call('db:seed', ['--force' => true]);

            $seederLog = Artisan::output();
        } catch (Exception $e) {
            dd($e);
        }

        return $seederLog;
    }
}
