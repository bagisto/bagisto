<?php

namespace Webkul\Installer\Http\Helpers;

use Exception;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (! file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return base_path('.env');
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath()
    {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @return string
     */
    public function saveFileClassic($request)
    {
        $message = trans('Success');

        if (! file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        try {
            $this->saveFileWizard($request->all());
        } catch (Exception $e) {
            $message = trans('Error');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @return string
     */
    public function saveFileWizard($request)
    {
        $message = trans('installer_messages.environment.success');

        $data = file($this->envPath);

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
        $envDBParams['DB_HOST'] = $request['db_hostname'];
        $envDBParams['DB_DATABASE'] = $request['db_name'];
        // $envDBParams['DB_PREFIX'] = $request["db_prefix"];
        $envDBParams['DB_USERNAME'] = $request['db_username'];
        $envDBParams['DB_PASSWORD'] = $request['db_password'];
        $envDBParams['APP_NAME'] = $request['app_name'];
        $envDBParams['APP_URL'] = $request['app_url'];
        $envDBParams['APP_CURRENCY'] = $request['app_currency'];
        $envDBParams['APP_LOCALE'] = $request['app_locale'];
        $envDBParams['APP_TIMEZONE'] = $request['app_timezone'];
        $envDBParams['DB_CONNECTION'] = $request['db_connection'];
        $envDBParams['DB_PORT'] = $request['db_port'];

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
            file_put_contents($this->envPath, $updatedEnvDBParams);
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }
}
