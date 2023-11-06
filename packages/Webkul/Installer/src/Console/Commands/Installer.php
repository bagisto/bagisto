<?php

namespace Webkul\Installer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class Installer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrate and seed command, publish assets and config, link storage';

    /**
     * Install and configure bagisto.
     */
    public function handle()
    {
        // Checking for .env
        $this->checkForEnvFile();

        // Loading values at runtime
        $this->loadEnvConfigAtRuntime();

        // Running `php artisan migrate`
        $this->warn('Step: Migrating all tables into database...');
        $this->info($this->call('migrate:fresh'));

        // Running `php artisan db:seed`
        $this->warn('Step: Seeding basic data for Bagisto kickstart...');
        $this->info($this->call('db:seed'));

        // Running `php artisan storage:link`
        $this->warn('Step: Linking storage directory...');
        $this->info($this->call('storage:link'));

        // Optimizing stuffs
        $this->warn('Step: Optimizing...');
        $this->info($this->call('optimize'));

        $this->warn('Step: Composer autoload...');
        $result = shell_exec('composer dump-autoload');
        $this->info($result);

        $this->createAdminCredential();
    }

    /**
     *  Checking .env file and if not found then create .env file.
     */
    protected function checkForEnvFile()
    {
        if (! file_exists(base_path('.env'))) {
            $this->info('Creating the environment configuration file.');

            File::copy('.env.example', '.env');
        } else {
            $this->info('Great! your environment configuration file already exists.');
        }

        $this->createEnvFile();

        $this->call('key:generate');
    }

    /**
     * Create a new .env file.
     * Then ask for env configuration details and set the environment
     * On .env file so that we can easily migrate to our db.
     *
     * @return void
     */
    protected function createEnvFile()
    {
        try {
            // Updating App URL
            $this->updateEnvVariable('APP_URL', 'Please Enter the APP URL or Press enter to Continue', 'http://localhost:8000');

            // Updating App Name
            $this->updateEnvVariable('APP_NAME', 'Please Enter the Application Name or Press enter to Continue', 'admin');

            // Updating App Default Locales
            $locales = ['ar', 'bn', 'de', 'en', 'es', 'fa', 'fr', 'he', 'hi_IN', 'it', 'ja', 'nl', 'pl', 'pt_BR', 'ru', 'sin', 'tr', 'uk', 'zh_CN'];
            $this->updateEnvChoice('APP_LOCALE', 'Please select the default locale or Press enter to Continue', $locales, 'en');

            $this->envUpdate('APP_TIMEZONE', date_default_timezone_get());
            $this->info('Your Default Timezone is ' . date_default_timezone_get());

            // Updating App Default Currencies
            $currencies = ['CNY', 'AED', 'EUR', 'INR', 'IRR', 'ILS', 'JPY', 'GBP', 'RUB', 'SAR', 'TRY', 'USD', 'UAH'];
            $this->updateEnvChoice('APP_CURRENCY', 'Please enter the default currency or Press enter to Continue', $currencies, 'USD');

            // Updating Database Configuration
            $this->askForDatabaseDetails();
        } catch (\Exception $e) {
            $this->error('Error in creating .env file, please create it manually and then run `php artisan migrate` again.');
        }
    }

    /**
     * Add the database credentials to the .env file.
     */
    protected function askForDatabaseDetails()
    {
        $connectionOptions = ['mysql', 'pgsql', 'sqlsrv'];

        $dbConnection = $this->choice('Please select the default Database Connection or Press enter to Continue', $connectionOptions, 0);

        if (! in_array($dbConnection, $connectionOptions)) {
            $this->error('Please select the valid Database Connection.');

            return $this->askForDatabaseDetails();
        }

        $dbDetails = [
            'DB_CONNECTION' => $dbConnection,
            'DB_HOST'       => $this->ask('Please Enter the Database Hostname or Press enter to Continue', '127.0.0.1'),
            'DB_PORT'       => $this->ask('Please Enter the Database Port number or Press enter to Continue', '3306'),
        ];

        // Here Asking Database Name, Prefix, Username, Password.
        $dbDetails['DB_DATABASE'] = $this->ask('Please Enter the database name to be used by Bagisto?');
        $dbDetails['DB_PREFIX'] = $this->ask('Please Enter the database prefix name?');
        $dbDetails['DB_USERNAME'] = $this->anticipate('Please Enter your database username?', ['root']);
        $dbDetails['DB_PASSWORD'] = $this->secret('Please Enter your database password?');

        if (! $dbDetails['DB_DATABASE'] || ! $dbDetails['DB_USERNAME'] || ! $dbDetails['DB_PASSWORD']) {
            return $this->error('Please Enter the database credentials.');
        }

        foreach ($dbDetails as $key => $value) {
            $this->envUpdate($key, $value);
        }
    }

    /**
     * Loaded Env variables for config files.
     *
     * @return void
     */
    protected function loadEnvConfigAtRuntime()
    {
        $this->warn('Loading configs...');

        // Environment directly checked from `.env` so changing in config won't reflect
        app()['env'] = $this->getEnvAtRuntime('APP_ENV');

        // setting for the first time and then `.env` values will be in charge
        config(['database.connections.mysql.database' => $this->getEnvAtRuntime('DB_DATABASE')]);
        config(['database.connections.mysql.username' => $this->getEnvAtRuntime('DB_USERNAME')]);
        config(['database.connections.mysql.password' => $this->getEnvAtRuntime('DB_PASSWORD')]);
        DB::purge('mysql');

        $this->info('Configuration loaded..');
    }

    /**
     * Check key in `.env` file because it will help to find values at runtime.
     *
     * @param  string  $key
     * @return string|bool
     */
    protected static function getEnvAtRuntime($key)
    {
        if ($data = file(base_path('.env'))) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);

                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    if (strpos($key, $rowValues[0]) !== false) {
                        return $rowValues[1];
                    }
                }
            }
        }

        return false;
    }

    /**
     * create a new admin credentials.
     */
    protected function createAdminCredential()
    {
        // Here! Asking for Admin Name, Email and Password
        $adminName = $this->ask('Please Enter the Name for admin User or press enter to continue', 'Example');
        $adminEmail = $this->ask('Please Enter the Email for admin login:', 'admin@example.com');
        $adminPassword = $this->ask('Please Enter the Password for admin login:', 'admin123');

        $password = password_hash($adminPassword, PASSWORD_BCRYPT, ['cost' => 10]);

        try {
            DB::table('admins')->updateOrInsert(
                ['id' => 1],
                [
                    'name'     => $adminName,
                    'email'    => $adminEmail,
                    'password' => $password,
                    'role_id'  => 1,
                    'status'   => 1,
                ]
            );

            $filePath = storage_path('installed');

            File::put($filePath, 'Your Bagisto App is Successfully Installed');

            $this->info('-----------------------------');
            $this->info('Congratulations!');
            $this->info('The installation has been finished and you can now use Bagisto.');
            $this->info('Go to ' . env('APP_URL') . '/admin' . ' and authenticate with:');
            $this->info('Email: ' . $adminEmail);
            $this->info('Password: ' . $adminPassword);
            $this->info('Cheers!');

            Event::dispatch('bagisto.installed');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * method for asking the details of .env files
     *
     * @param  string  $key
     * @param  string  $question
     * @param  string  $defaultValue
     * @return void
     */
    protected function updateEnvVariable($key, $question, $defaultValue)
    {
        $input = $this->ask($question, $defaultValue);

        $this->envUpdate($key, $input ?: $defaultValue);
    }

    /**
     * Method for asking choice based on the list of options.
     *
     * @param  string  $key
     * @param  string  $question
     * @param  array  $choices
     * @param  string  $defaultValue
     * @return void
     */
    protected function updateEnvChoice($key, $question, $choices, $defaultValue)
    {
        $choice = $this->choice($question, $choices, array_search($defaultValue, $choices));

        $this->envUpdate("$key", $choice);
    }

    /**
     * Update the .env values
     *
     * @param  string  $key
     * @param  string  $value
     * @return void
     */
    protected static function envUpdate($key, $value)
    {
        $data = file_get_contents(base_path('.env'));

        $data = preg_replace("/$key=(.*)/", "$key=$value", $data);

        file_put_contents(base_path('.env'), $data);
    }
}
