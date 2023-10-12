<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;
use Webkul\Installer\Http\Helpers\DatabaseManager;

class EnvironmentManager
{
    public function __construct(protected DatabaseManager $databaseManager)
    {
    }

    /**
     * Generate ENV File and Installation.
     *
     * @param [object] $request
     * @return string
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

            if ($response) {
                $installation = $this->databaseManager->migration();

                return $installation;
            }

            return 'Something went wrong in your Environment Update. Please try again';
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
        $message = trans('success');

        $data = file(base_path('.env'));

        $envDBParams = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);

                $rowValues = explode('=', $line);

                if (! strlen($line)) {
                    continue;
                }

                $envDBParams[$rowValues[0]] = $rowValues[1];
            }
        }

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
            $envDBParams['DB_PORT'] = (int) ($request['db_port']);
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

        /**
         * Making key/value pair with form-data for env
         */
        $updatedEnvDBParams = [];

        foreach ($envDBParams as $key => $value) {
            $updatedEnvDBParams[] = $key . '=' . $value;
        }

        /**
         * Inserting new form-data to env
         */
        $updatedEnvDBParams = implode(PHP_EOL, $updatedEnvDBParams);

        try {
            file_put_contents(base_path('.env'), $updatedEnvDBParams);
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }
}
