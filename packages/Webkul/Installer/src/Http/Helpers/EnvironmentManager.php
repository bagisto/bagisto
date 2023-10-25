<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;

class EnvironmentManager
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Installer\Http\Helpers\DatabaseManager  $databaseManager
     * @return void
     */
    public function __construct(protected DatabaseManager $databaseManager)
    {
    }

    /**
     * Generate ENV File and Installation.
     *
     * @param [object] $request
     * @return
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
            $envDBParams['APP_NAME'] = $request['app_name'];
            $envDBParams['APP_URL'] = $request['app_url'];
            $envDBParams['APP_CURRENCY'] = $request['app_currency'];
            $envDBParams['APP_LOCALE'] = $request['app_locale'];
            $envDBParams['APP_TIMEZONE'] = $request['app_timezone'];
            $envDBParams['DB_CONNECTION'] = $request['db_connection'];
            $envDBParams['DB_PORT'] = (int) $request['db_port'];
        }

        if (isset($request['mail_host'])) {
            $envDBParams['MAIL_MAILER'] = 'smtp';
            $envDBParams['MAIL_HOST'] = $request['mail_host'];
            $envDBParams['MAIL_PORT'] = $request['mail_port'];
            $envDBParams['MAIL_USERNAME'] = $request['mail_username'];
            $envDBParams['MAIL_PASSWORD'] = $request['mail_password'];
            $envDBParams['MAIL_ENCRYPTION'] = $request['mail_encryption'];
            $envDBParams['MAIL_FROM_ADDRESS'] = $request['mail_from_address'];
        }

        $data = file_get_contents(base_path('.env'));

        foreach ($envDBParams as $key => $value) {
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
