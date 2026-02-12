<?php

namespace Webkul\Installer\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class EnvironmentManager
{
    /**
     * Generate `.env` file for installation.
     */
    public function generateEnv(array $data): bool|Exception
    {
        $envExamplePath = base_path('.env.example');

        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            if (file_exists($envExamplePath)) {
                copy($envExamplePath, $envPath);
            } else {
                touch($envPath);
            }
        }

        return $this->updateEnvVariables($data);
    }

    /**
     * Get environment variable value from `.env` file.
     *
     * @param  mixed  $default
     */
    public function getEnvVariable(string $key, $default = null): string|bool
    {
        if ($data = file(base_path('.env'))) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);

                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    if (strpos($key, $rowValues[0]) !== false) {
                        return trim($rowValues[1], '"');
                    }
                }
            }
        }

        return $default;
    }

    /**
     * Update a single environment variable in `.env` file.
     */
    public function updateEnvVariable(string $key, string $value, bool $addQuotes = false): void
    {
        $data = file_get_contents(base_path('.env'));

        // Check if $value contains spaces, and if so, add double quotes, or if $addQuotes is true.
        if ($addQuotes || preg_match('/\s/', $value)) {
            $value = '"'.$value.'"';
        }

        $data = preg_replace("/$key=(.*)/", "$key=$value", $data);

        file_put_contents(base_path('.env'), $data);
    }

    /**
     * Update multiple environment variables in `.env` file.
     */
    public function updateEnvVariables(array $data): bool
    {
        $envParams = [];

        if (isset($data['app_name'])) {
            $envParams['APP_NAME'] = $data['app_name'] ?? null;
            $envParams['APP_URL'] = $data['app_url'];
            $envParams['APP_CURRENCY'] = $data['app_currency'];
            $envParams['APP_LOCALE'] = $data['app_locale'];
            $envParams['APP_TIMEZONE'] = $data['app_timezone'];
        }

        if (isset($data['db_hostname'])) {
            $envParams['DB_HOST'] = $data['db_hostname'];
            $envParams['DB_DATABASE'] = $data['db_name'];
            $envParams['DB_PREFIX'] = $data['db_prefix'] ?? '';
            $envParams['DB_USERNAME'] = $data['db_username'];
            $envParams['DB_PASSWORD'] = $data['db_password'];
            $envParams['DB_CONNECTION'] = $data['db_connection'];
            $envParams['DB_PORT'] = (int) $data['db_port'];
        }

        try {
            foreach ($envParams as $key => $value) {
                $this->updateEnvVariable($key, (string) $value);
            }

            return true;
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Load environment configurations and set up database connection for installation.
     */
    public function loadEnvConfigs(): void
    {
        /**
         * Setting application environment.
         */
        app()['env'] = $this->getEnvVariable('APP_ENV');

        /**
         * Setting application configuration.
         */
        config([
            'app.env' => $this->getEnvVariable('APP_ENV'),
            'app.name' => $this->getEnvVariable('APP_NAME'),
            'app.url' => $this->getEnvVariable('APP_URL'),
            'app.timezone' => $this->getEnvVariable('APP_TIMEZONE'),
            'app.locale' => $this->getEnvVariable('APP_LOCALE'),
            'app.currency' => $this->getEnvVariable('APP_CURRENCY'),
        ]);

        /**
         * Setting database configurations.
         */
        $databaseConnection = $this->getEnvVariable('DB_CONNECTION');

        DB::purge();

        config([
            "database.connections.{$databaseConnection}.host" => $this->getEnvVariable('DB_HOST'),
            "database.connections.{$databaseConnection}.port" => $this->getEnvVariable('DB_PORT'),
            "database.connections.{$databaseConnection}.database" => $this->getEnvVariable('DB_DATABASE'),
            "database.connections.{$databaseConnection}.username" => $this->getEnvVariable('DB_USERNAME'),
            "database.connections.{$databaseConnection}.password" => $this->getEnvVariable('DB_PASSWORD'),
            "database.connections.{$databaseConnection}.prefix" => $this->getEnvVariable('DB_PREFIX'),
        ]);

        DB::reconnect();

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            report($e);

            abort(400);
        }
    }

    /**
     * Generate application key.
     */
    public function generateKey()
    {
        Artisan::call('key:generate');
    }

    /**
     * Storage link.
     */
    public function storageLink(): void
    {
        Artisan::call('storage:link');
    }
}
