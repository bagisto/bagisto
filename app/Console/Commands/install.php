<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class install extends Command
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
     * Install and configure bagisto
     */
    public function handle()
    {
        $this->checkForEnvFile();

        // running `php artisan migrate`
        $this->warn('Step: Migrating all tables into database...');
        $migrate = shell_exec('php artisan migrate:fresh');
        $this->info($migrate);

        // running `php artisan db:seed`
        $this->warn('Step: seeding basic data for bagisto kickstart...');
        $result = shell_exec('php artisan db:seed');
        $this->info($result);

        // running `php artisan vendor:publish --all`
        $this->warn('Step: Publishing Assets and Configurations...');
        $result = shell_exec('php artisan vendor:publish --all');
        $this->info($result);

        // running `php artisan storage:link`
        $this->warn('Step: Linking Storage directory...');
        $result = shell_exec('php artisan storage:link');
        $this->info($result);

        // running `composer dump-autoload`
        $this->warn('Step: Composer Autoload...');
        $result = shell_exec('composer dump-autoload');
        $this->info($result);

        $this->info('-----------------------------');
        $this->info('Now, run `php artisan serve` to start using Bagisto');
        $this->info('Cheers!');
    }

    /**
    *  Checking .env file and if not found then create .env file.
    *  Then ask for database name, password & username to set
    *  On .env file so that we can easily migrate to our db
    */
    public function checkForEnvFile()
    {
        $envExists = File::exists(base_path() . '/.env');
        if (!$envExists) {
            $this->info('Creating .env file');
            $this->createEnvFile();
        } else {
            $this->info('Great! .env file aready exists');
        }
    }

    public function createEnvFile()
    {
        try {
            File::copy('.env.example', '.env');
            Artisan::call('key:generate');
            $this->envUpdate('APP_URL=http://localhost', ':8000');
            $this->addDatabaseDetails();
        } catch (\Exception $e) {
            $this->error('Error in creating .env file, please create manually and then run `php artisan migrate` again');
        }
    }

    public function addDatabaseDetails()
    {
        $dbName = $this->ask('What is your database name to be used by bagisto');
        $dbUser = $this->anticipate('What is your database username', ['root']);
        $dbPass = $this->secret('What is your database password');
        $this->envUpdate('DB_DATABASE=', $dbName);
        $this->envUpdate('DB_USERNAME=', $dbUser);
        $this->envUpdate('DB_PASSWORD=', $dbPass);
    }

    public static function envUpdate($key, $value)
    {
        $path  = base_path() . '/.env';
        file_put_contents($path, str_replace(
            $key,
            $key . $value,
            file_get_contents($path)
        ));
    }
}
