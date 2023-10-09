<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseManager
{
    /**
     * Migrate and seed the database.
     *
     * @return array
     */
    public function migrateAndSeed()
    {
        $outputLog = new BufferedOutput;

        return $this->migrate($outputLog);
    }

    public function getEnvironment($params = [])
    {
        $connection = [
            'DB_HOST'       => $params['DB_HOST'] ?? env('DB_HOST'),
            'DB_DATABASE'   => $params['DB_DATABASE'] ?? env('DB_DATABASE'),
            'DB_USERNAME'   => $params['DB_USERNAME'] ?? env('DB_USERNAME'),
            'DB_PASSWORD'   => $params['DB_PASSWORD'] ?? env('DB_PASSWORD'),
            'DB_CONNECTION' => $params['DB_CONNECTION'] ?? env('DB_CONNECTION'),
            'DB_PORT'       => $params['DB_PORT'] ?? env('DB_PORT'),
            'DB_PREFIX'     => $params['DB_PREFIX'] ?? env('DB_PREFIX'),
        ];

        try {
            DB::connection()->getPdo();
            if (DB::connection()->getDatabaseName()) {
                echo 'Database connection established successfully.';
            } else {
                $database = DB::connection()->getDatabaseName();

                if (! file_exists($database)) {
                    touch($database);
                    DB::reconnect(Config::get('database.default'));

                    return $connection;
                }
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Run the migration and call the seeder.
     *
     * @return array
     */
    private function migrate(BufferedOutput $outputLog)
    {
        try {
            Artisan::call('migrate', ['--force'=> true], $outputLog);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->seed($outputLog);
    }

    /**
     * Seed the database.
     *
     * @return array
     */
    private function seed(BufferedOutput $outputLog)
    {
        try {
            Artisan::call('db:seed', ['--force' => true], $outputLog);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->response(trans('installer_messages.final.finished'), 'success', $outputLog);
    }

    /**
     * Return a formatted error messages.
     *
     * @param  string  $message
     * @param  string  $status
     * @return array
     */
    private function response($message, $status, BufferedOutput $outputLog)
    {
        return [
            'status'      => $status,
            'message'     => $message,
            'dbOutputLog' => $outputLog->fetch(),
        ];
    }
}
