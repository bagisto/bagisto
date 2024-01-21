<?php

namespace Webkul\Installer\Helpers;

use Exception;

class EnvironmentManager
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(protected DatabaseManager $databaseManager)
    {
    }

    /**
     * Generate ENV File and Installation.
     *
     * @param [object] $request
     */
    public function generateEnv($request)
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

        try {
            $response = $this->setEnvConfiguration($request->all());

            $this->databaseManager->generateKey();

            return $response;
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Set the ENV file configuration.
     *
     * @return string
     */
    public function setEnvConfiguration($request)
    {
        $envDBParams = [];

        /**
         * Update params with form-data
         */
        if (isset($request['db_hostname'])) {
            $envDBParams['DB_HOST'] = $request['db_hostname'];
            $envDBParams['DB_DATABASE'] = $request['db_name'];
            $envDBParams['DB_PREFIX'] = $request['db_prefix'] ?? '';
            $envDBParams['DB_USERNAME'] = $request['db_username'];
            $envDBParams['DB_PASSWORD'] = $request['db_password'];
            $envDBParams['DB_CONNECTION'] = $request['db_connection'];
            $envDBParams['DB_PORT'] = (int) $request['db_port'];
        }

        if (isset($request['app_name'])) {
            $envDBParams['APP_NAME'] = $request['app_name'] ?? null;
            $envDBParams['APP_URL'] = $request['app_url'];
            $envDBParams['APP_CURRENCY'] = $request['app_currency'];
            $envDBParams['APP_LOCALE'] = $request['app_locale'];
            $envDBParams['APP_TIMEZONE'] = $request['app_timezone'];
        }

        $data = file_get_contents(base_path('.env'));

        foreach ($envDBParams as $key => $value) {
            if (preg_match('/\s/', $value)) {
                $value = '"'.$value.'"';
            }

            $data = preg_replace("/$key=(.*)/", "$key=$value", $data);
        }

        try {
            file_put_contents(base_path('.env'), $data);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
