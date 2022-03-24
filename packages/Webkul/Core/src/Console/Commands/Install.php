<?php

namespace Webkul\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Install extends Command
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
        // check for .env
        $this->checkForEnvFile();

        // loading values at runtime
        $this->loadEnvConfigAtRuntime();

        // running `php artisan migrate`
        $this->warn('Step: Migrating all tables into database...');
        $migrate = $this->call('migrate:fresh');
        $this->info($migrate);

        // running `php artisan db:seed`
        $this->warn('Step: Seeding basic data for Bagisto kickstart...');
        $result = $this->call('db:seed');
        $this->info($result);

        // running `php artisan vendor:publish --all`
        $this->warn('Step: Publishing assets and configurations...');
        $result = $this->call('vendor:publish', ['--all' => true, '--force' => true]);
        $this->info($result);

        // running `php artisan storage:link`
        $this->warn('Step: Linking storage directory...');
        $result = $this->call('storage:link');
        $this->info($result);

        // optimizing stuffs
        $this->warn('Step: Optimizing...');
        $result = $this->call('optimize');
        $this->info($result);

        // running `composer dump-autoload`
        $this->warn('Step: Composer autoload...');
        $result = shell_exec('composer dump-autoload');
        $this->info($result);

        // removing the installer directory
        if (is_dir('public/installer')) {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                shell_exec('rmdir /s/q public\\installer');
            } else {
                shell_exec('rm -rf public/installer');
            }
        }

        // final information
        $this->info('-----------------------------');
        $this->info('Congratulations!');
        $this->info('The installation has been finished and you can now use Bagisto.');
        $this->info('Go to '. url(config('app.admin_url')) .' and authenticate with:');
        $this->info('Email: admin@example.com');
        $this->info('Password: admin123');
        $this->info('Cheers!');
    }

    /**
    *  Checking .env file and if not found then create .env file.
    *  Then ask for database name, password & username to set
    *  On .env file so that we can easily migrate to our db.
    */
    protected function checkForEnvFile()
    {
        $envExists = File::exists(base_path() . '/.env');

        if (! $envExists) {
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

            $default_app_url =  'http://localhost:8000';
            $input_app_url = $this->ask('Please Enter the APP URL : ');
            $this->envUpdate('APP_URL=', $input_app_url ? $input_app_url : $default_app_url );

            $default_admin_url =  'admin';
            $input_admin_url = $this->ask('Please Enter the Admin URL : ');
            $this->envUpdate('APP_ADMIN_URL=', $input_admin_url ?: $default_admin_url);

            $locale = $this->choice('Please select the default locale or press enter to continue', ['ar', 'en', 'es', 'fa', 'nl', 'pt_BR'], 1);
            $this->envUpdate('APP_LOCALE=', $locale);

            $TimeZones = timezone_identifiers_list();
            $timezone = $this->anticipate('Please enter the default timezone', $TimeZones, date_default_timezone_get());
            $this->envUpdate('APP_TIMEZONE=', $timezone);

            $currency = $this->choice('Please enter the default currency', ['USD', 'EUR'], 'USD');
            $this->envUpdate('APP_CURRENCY=', $currency);

            $this->addDatabaseDetails();
        } catch (\Exception $e) {
            $this->error('Error in creating .env file, please create it manually and then run `php artisan migrate` again.');
        }
    }

    /**
     * Add the database credentials to the .env file.
     */
    protected function addDatabaseDetails()
    {
        $dbName = $this->ask('What is the database name to be used by bagisto?');
        $this->envUpdate('DB_DATABASE=', $dbName);

        $dbUser = $this->anticipate('What is your database username?', ['root']);
        $this->envUpdate('DB_USERNAME=', $dbUser);

        $dbPass = $this->secret('What is your database password?');
        $this->envUpdate('DB_PASSWORD=', $dbPass);
    }

    /**
     * Load `.env` config at runtime.
     */
    protected function loadEnvConfigAtRuntime()
    {
        $this->warn('Loading configs...');

        /* environment directly checked from `.env` so changing in config won't reflect */
        app()['env'] = $this->getEnvAtRuntime('APP_ENV');

        /* setting for the first time and then `.env` values will be incharged */
        config(['database.connections.mysql.database' => $this->getEnvAtRuntime('DB_DATABASE')]);
        config(['database.connections.mysql.username' => $this->getEnvAtRuntime('DB_USERNAME')]);
        config(['database.connections.mysql.password' => $this->getEnvAtRuntime('DB_PASSWORD')]);
        DB::purge('mysql');

        $this->info('Configuration loaded..');
    }

    /**
     * Check key in `.env` file because it will help to find values at runtime.
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

    /**
     * Update the .env values.
     */
    protected static function envUpdate($key, $value)
    {
        $path = base_path() . '/.env';
        $data = file($path);
        $keyValueData = $changedData = [];

        if ($data) {
            foreach ($data as $line) {
                $line = preg_replace('/\s+/', '', $line);
                $rowValues = explode('=', $line);

                if (strlen($line) !== 0) {
                    $keyValueData[$rowValues[0]] = $rowValues[1];

                    if (strpos($key, $rowValues[0]) !== false) {
                        $keyValueData[$rowValues[0]] = $value;
                    }
                }
            }
        }

        foreach ($keyValueData as $key => $value) {
            $changedData[] = $key . '=' . $value;
        }

        $changedData = implode(PHP_EOL, $changedData);

        file_put_contents($path, $changedData);
    }
}
