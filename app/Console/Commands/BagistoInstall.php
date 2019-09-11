<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BagistoInstall extends Command
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
    protected $description = 'This command will try to configure and install new Bagisto instance';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = base_path('.env');

        $uname = strtolower(\php_uname());

        if (str_contains($uname, 'win'))
            shell_exec('copy .env.example .env');
        else
            shell_exec('cp .env.example .env');

        $this->line('Hello and welcome to Bagisto command line installer.');

        $APP_NAME = $this->ask('[KEY = "APP_NAME"] Please enter name of application instance?');
        $this->comment($APP_NAME ?? 'Bagisto');
        $this->changeEnvironmentVariable('APP_NAME', $APP_NAME);

        $APP_ENV = $this->choice('[KEY = "APP_ENV"] Choose environment for this instance of Bagisto?', ['development', 'production'], 0);
        $this->comment($APP_ENV);
        $this->changeEnvironmentVariable('APP_ENV', $APP_ENV);

        // $APP_DEBUG = $this->ask('Choose APP_DEBUG?', ['true', 'false'], 0);
        // $this->comment($APP_DEBUG);
        // $this->changeEnvironmentVariable('APP_DEBUG', $APP_DEBUG);

        $APP_URL = $this->ask('[KEY = "APP_URL"] Please enter application URL (Optional, default = localhost)?');
        $this->comment($APP_URL ?? 'localhost');
        $this->changeEnvironmentVariable('APP_URL', $APP_URL);

        $DB_CONNECTION = $this->ask('[KEY = "DB_CONNECTION"] Enter database connection (Optional, default = mysql)?');
        $this->comment($DB_CONNECTION ?? 'mysql');
        $this->changeEnvironmentVariable('DB_CONNECTION', $DB_CONNECTION);

        $DB_HOST = $this->ask('[KEY = "DB_HOST"] Enter database host (Optional, default = 127.0.0.1)?');
        $this->comment($DB_HOST ?? '127.0.0.1');
        $this->changeEnvironmentVariable('DB_HOST', $DB_HOST);

        $DB_PORT = $this->ask('[KEY = "DB_PORT"] Enter database port (Optional, default = 3306)?');
        $this->comment($DB_PORT ?? '3306');
        $this->changeEnvironmentVariable('DB_PORT', $DB_PORT);

        $DB_DATABASE = null;

        while(! isset($DB_DATABASE)) {
            $DB_DATABASE = $this->ask('[KEY = "DB_DATABASE"] Enter name of database?');
            $this->comment($DB_DATABASE ?? 'forge');
        }

        $this->changeEnvironmentVariable('DB_DATABASE', $DB_DATABASE);

        while(! isset($DB_USERNAME)) {
            $DB_USERNAME = $this->ask('Enter database username?');
            $this->comment($DB_USERNAME ?? 'root');
        }

        $this->changeEnvironmentVariable('[KEY = "DB_USERNAME"] DB_USERNAME', $DB_USERNAME);

        $DB_PASSWORD = $this->ask('Please enter database password?');
        $this->comment($DB_PASSWORD);
        $this->changeEnvironmentVariable('[KEY = "DB_PASSWORD"] DB_PASSWORD', $DB_PASSWORD);

        $this->info('We are done with application and database params, good job!');

        $this->info('Do you want me to be mail ready, i am loaded with notifications!');

        $MAIL_DRIVER = $this->ask('[KEY = "MAIL_DRIVER"] Enter MAIL_DRIVER (Optional, default = smtp)?');
        $this->comment($MAIL_DRIVER ?? 'smtp');
        $this->changeEnvironmentVariable('MAIL_DRIVER', $MAIL_DRIVER ?? 'smtp');

        $MAIL_HOST = $this->ask('[KEY = "MAIL_HOST"] Enter MAIL_HOST (Optional, default = smtp.mailtrap.io)?');
        $this->comment($MAIL_HOST ?? 'smtp.mailtrap.io');
        $this->changeEnvironmentVariable('MAIL_HOST', $MAIL_HOST ?? 'smtp.mailtrap.io');

        $MAIL_PORT = $this->ask('[KEY = "MAIL_PORT"] Enter MAIL_PORT (Optional, default = 2525)?');
        $this->comment($MAIL_PORT ?? '2525');
        $this->changeEnvironmentVariable('MAIL_PORT', $MAIL_PORT ?? '2525');

        $MAIL_USERNAME = $this->ask('[KEY = "MAIL_USERNAME"] Enter MAIL_USERNAME?');
        $this->comment($MAIL_USERNAME ?? null);
        $this->changeEnvironmentVariable('MAIL_USERNAME', $MAIL_USERNAME);

        $MAIL_PASSWORD = $this->ask('[KEY = "MAIL_PASSWORD"] Enter MAIL_PASSWORD?');
        $this->comment($MAIL_PASSWORD ?? null);
        $this->changeEnvironmentVariable('MAIL_PASSWORD', $MAIL_PASSWORD);

        $MAIL_ENCRYPTION = $this->ask('[KEY = "MAIL_ENCRYPTION"] Enter MAIL_ENCRYPTION (default = tls)?');
        $this->comment($MAIL_ENCRYPTION ?? 'tls');
        $this->changeEnvironmentVariable('MAIL_ENCRYPTION', $MAIL_ENCRYPTION ?? 'tls');

        $SHOP_MAIL_FROM = $this->ask('[KEY = "SHOP_MAIL_FROM"] Enter SHOP_MAIL_FROM?');
        $this->comment($SHOP_MAIL_FROM ?? null);
        $this->changeEnvironmentVariable('SHOP_MAIL_FROM', $SHOP_MAIL_FROM);

        $ADMIN_MAIL_TO = $this->ask('[KEY = "ADMIN_MAIL_TO"] Enter ADMIN_MAIL_TO?');
        $this->comment($ADMIN_MAIL_TO ?? null);
        $this->changeEnvironmentVariable('ADMIN_MAIL_TO', $ADMIN_MAIL_TO);

        $this->info('We are done setting all the configuration in the env file, now we will proceed by running the commands necessary for Bagisto');

        \Artisan::call('config:cache');

        \Artisan::call('migrate');

        $this->info('Migrations complete');

        \Artisan::call('db:seed', ['--force' => true]);

        $this->info('Seeding complete');

        \Artisan::call('storage:link');

        \Artisan::call('vendor:publish', [0]);

        $this->info('Installation completed.');

        $this->info('Please write us: SUPPORT@BAGISTO.COM');

        $this->info('Thank you!!!');
    }

    public static function changeEnvironmentVariable($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents($path)
            ));
        }
    }
}