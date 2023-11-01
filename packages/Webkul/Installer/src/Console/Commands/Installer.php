<?php

namespace Webkul\Installer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
     * The entity to create.
     *
     * @var string
     */
    protected $entity = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Install and configure bagisto.
     */
    public function handle()
    {
        /**
         * Check for .env
         */
        $this->checkForEnvFile();

        /**
         * Loading values at runtime
         */
        $this->loadEnvConfigAtRuntime();

        /**
         * Running `php artisan migrate`
         */
        $this->warn('Step: Migrating all tables into database...');
        $migrate = $this->call('migrate:fresh');
        $this->info($migrate);

        /**
         * Running `php artisan db:seed`
         */
        $this->warn('Step: Seeding basic data for Bagisto kickstart...');
        $result = $this->call('db:seed');
        $this->info($result);

        /**
         * Running `php artisan bagisto:publish --force`
         */
        $this->warn('Step: Publishing assets and configurations...');
        $result = $this->call('bagisto:publish', ['--force' => true]);
        $this->info($result);

        /**
         * Running `php artisan storage:link`
         */
        $this->warn('Step: Linking storage directory...');
        $result = $this->call('storage:link');
        $this->info($result);

        /**
         * Optimizing stuffs
         */
        $this->warn('Step: Optimizing...');
        $result = $this->call('optimize');
        $this->info($result);

        /**
         * Running `composer dump-autoload`
         */
        $this->warn('Step: Composer autoload...');
        $result = shell_exec('composer dump-autoload');
        $this->info($result);

        /**
         * Removing the installer directory
         */
        if (is_dir('public/installer')) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                shell_exec('rmdir /s/q public\\installer');
            } else {
                shell_exec('rm -rf public/installer');
            }
        }

        $this->createAdminCredential();
    }

    /**
     *  Checking .env file and if not found then create .env file.
     *  Then ask for database name, password & username to set
     *  On .env file so that we can easily migrate to our db.
     */
    protected function checkForEnvFile()
    {
        if (! file_exists(base_path('.env'))) {
            $this->info('Creating the environment configuration file.');

            $this->createEnvFile();
        } else {
            $this->info('Great! your environment configuration file already exists.');
        }

        $this->call('key:generate');
    }

    /**
     * Create a new .env file.
     */
    protected function createEnvFile()
    {
        try {
            File::copy('.env.example', '.env');

            $this->updateEnvVariable('APP_URL', 'Please Enter the APP URL', 'http://localhost:8000');

            $this->updateEnvVariable('APP_ADMIN_URL', 'Please Enter the Admin Name', 'admin');

            $locales = ['ar', 'en', 'es', 'fa', 'nl', 'pt_BR'];
            $this->updateEnvChoice('APP_LOCALE', 'Please select the default locale', $locales, 'en');

            $timeZones = timezone_identifiers_list();
            $this->updateEnvChoice('APP_TIMEZONE', 'Please enter the default timezone', $timeZones, date_default_timezone_get());

            $currencies = ['USD', 'EUR'];
            $this->updateEnvChoice('APP_CURRENCY', 'Please enter the default currency', $currencies, 'USD');

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
        $connectionOptions = ['Mysql', 'SQlite', 'pgSQL', 'SQLSRV'];

        $dbConnection = $this->choice('Please select the default Database Connection or press enter to continue', $connectionOptions, 0);

        if (! in_array($dbConnection, $connectionOptions)) {
            return $this->askForDatabaseDetails();
        }

        $dbDetails = [
            'DB_CONNECTION' => $dbConnection,
            'DB_HOST'       => $this->ask('Please Enter the Database Hostname or press enter to continue', '127.0.0.1') ?? '127.0.0.1',
            'DB_PORT'       => $this->ask('Please Enter the Database Port to be used in Bagisto', '3306') ?? '3306',
        ];

        $dbName = $this->ask('What is the database name to be used by Bagisto?');

        if (! $dbName) {
            return $this->error('Please Enter the database name.');
        }

        $dbDetails['DB_DATABASE'] = $dbName;

        $dbPrefix = $this->ask('What is the database prefix name?');

        $dbUser = $this->anticipate('What is your database username?', ['root']);

        $dbPass = $this->secret('What is your database password?');

        if (! $dbUser || ! $dbPass) {
            return $this->error('Please Enter the database username and password.');
        }

        $dbDetails['DB_PREFIX'] = $dbPrefix;
        $dbDetails['DB_USERNAME'] = $dbUser;
        $dbDetails['DB_PASSWORD'] = $dbPass;

        foreach ($dbDetails as $key => $value) {
            $this->envUpdate($key . '=', $value);
        }
    }

    protected function loadEnvConfigAtRuntime()
    {
        $this->warn('Loading configs...');

        /**
         * Environment directly checked from `.env` so changing in config won't reflect
         */
        app()['env'] = $this->getEnvAtRuntime('APP_ENV');

        /* setting for the first time and then `.env` values will be in charge */
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
        $path = base_path() . '/.env';
        $data = file($path);

        if ($data) {
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

    protected function createAdminCredential()
    {
        $adminName = $this->ask('Please Enter the Admin for admin User :') ?? 'Admin';
        $adminEmail = $this->ask('Please Enter the email for admin login:') ?? 'admin@example.com';
        $adminPassword = $this->ask('Please Enter the password for admin login:') ?? 'admin@123';

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

            $this->info('-----------------------------');
            $this->info('Congratulations!');
            $this->info('The installation has been finished and you can now use Bagisto.');
            $this->info('Go to ' . url(config('app.admin_url')) . ' and authenticate with:');
            $this->info('Email: ' . $adminEmail);
            $this->info('Password: ' . $adminPassword);
            $this->info('Cheers!');
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function updateEnvVariable($key, $question, $defaultValue)
    {
        $input = $this->ask($question);
        $this->envUpdate("$key=", $input ?: $defaultValue);
    }

    protected function updateEnvChoice($key, $question, $choices, $defaultValue)
    {
        $choice = $this->choice($question, $choices, array_search($defaultValue, $choices));
        $this->envUpdate("$key=", $choice);
    }

    /**
     * Update the .env values.
     *
     * @param  string  $key
     * @param  string  $value
     * @return string
     */
    protected static function envUpdate($key, $value)
    {
        $envFilePath = base_path('.env');

        $data = file_get_contents($envFilePath);

        $pattern = "/^$key=.*/m";

        if (preg_match($pattern, $data)) {
            $data = preg_replace($pattern, "$key=$value", $data);
        } else {
            $data .= "$key=$value\n";
        }

        file_put_contents($envFilePath, $data);
    }
}
