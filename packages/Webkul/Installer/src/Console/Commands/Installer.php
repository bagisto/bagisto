<?php

namespace Webkul\Installer\Console\Commands;

use DateTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;
use Webkul\Installer\Events\ComposerEvents;
use Webkul\Installer\Helpers\DatabaseManager;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class Installer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:install
        { --skip-env-check : Skip env check. }
        { --skip-admin-creation : Skip admin creation. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bagisto installer.';

    /**
     * Environment details.
     *
     * @var array
     */
    protected $envDetails = [];

    /**
     * Fillable environment variables.
     *
     * @var array
     */
    protected $fillableEnvVariables = [
        'APP_NAME',
        'APP_URL',
        'APP_TIMEZONE',
        'APP_LOCALE',
        'APP_CURRENCY',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_PREFIX',
        'DB_USERNAME',
        'DB_PASSWORD',
    ];

    /**
     * Locales list.
     *
     * @var array
     */
    protected $locales = [
        'ar'    => 'Arabic',
        'bn'    => 'Bengali',
        'de'    => 'German',
        'en'    => 'English',
        'es'    => 'Spanish',
        'fa'    => 'Persian',
        'fr'    => 'French',
        'he'    => 'Hebrew',
        'hi_IN' => 'Hindi',
        'it'    => 'Italian',
        'ja'    => 'Japanese',
        'nl'    => 'Dutch',
        'pl'    => 'Polish',
        'pt_BR' => 'Brazilian Portuguese',
        'ru'    => 'Russian',
        'sin'   => 'Sinhala',
        'tr'    => 'Turkish',
        'uk'    => 'Ukrainian',
        'zh_CN' => 'Chinese',
    ];

    /**
     * Currencies list.
     *
     * @var array
     */
    protected $currencies = [
        'AED' => 'United Arab Emirates Dirham',
        'ARS' => 'Argentine Peso',
        'AUD' => 'Australian Dollar',
        'BDT' => 'Bangladeshi Taka',
        'BHD' => 'Bahraini Dinar',
        'BRL' => 'Brazilian Real',
        'CAD' => 'Canadian Dollar',
        'CHF' => 'Swiss Franc',
        'CLP' => 'Chilean Peso',
        'CNY' => 'Chinese Yuan',
        'COP' => 'Colombian Peso',
        'CZK' => 'Czech Koruna',
        'DKK' => 'Danish Krone',
        'DZD' => 'Algerian Dinar',
        'EGP' => 'Egyptian Pound',
        'EUR' => 'Euro',
        'FJD' => 'Fijian Dollar',
        'GBP' => 'British Pound Sterling',
        'HKD' => 'Hong Kong Dollar',
        'HUF' => 'Hungarian Forint',
        'IDR' => 'Indonesian Rupiah',
        'ILS' => 'Israeli New Shekel',
        'INR' => 'Indian Rupee',
        'JOD' => 'Jordanian Dinar',
        'JPY' => 'Japanese Yen',
        'KRW' => 'South Korean Won',
        'KWD' => 'Kuwaiti Dinar',
        'KZT' => 'Kazakhstani Tenge',
        'LBP' => 'Lebanese Pound',
        'LKR' => 'Sri Lankan Rupee',
        'LYD' => 'Libyan Dinar',
        'MAD' => 'Moroccan Dirham',
        'MUR' => 'Mauritian Rupee',
        'MXN' => 'Mexican Peso',
        'MYR' => 'Malaysian Ringgit',
        'NGN' => 'Nigerian Naira',
        'NOK' => 'Norwegian Krone',
        'NPR' => 'Nepalese Rupee',
        'NZD' => 'New Zealand Dollar',
        'OMR' => 'Omani Rial',
        'PAB' => 'Panamanian Balboa',
        'PEN' => 'Peruvian Nuevo Sol',
        'PHP' => 'Philippine Peso',
        'PKR' => 'Pakistani Rupee',
        'PLN' => 'Polish Zloty',
        'PYG' => 'Paraguayan Guarani',
        'QAR' => 'Qatari Rial',
        'RON' => 'Romanian Leu',
        'RUB' => 'Russian Ruble',
        'SAR' => 'Saudi Riyal',
        'SEK' => 'Swedish Krona',
        'SGD' => 'Singapore Dollar',
        'THB' => 'Thai Baht',
        'TND' => 'Tunisian Dinar',
        'TRY' => 'Turkish Lira',
        'TWD' => 'New Taiwan Dollar',
        'UAH' => 'Ukrainian Hryvnia',
        'USD' => 'United States Dollar',
        'UZS' => 'Uzbekistani Som',
        'VEF' => 'Venezuelan Bolívar',
        'VND' => 'Vietnamese Dong',
        'XAF' => 'CFA Franc BEAC',
        'XOF' => 'CFA Franc BCEAO',
        'ZAR' => 'South African Rand',
        'ZMW' => 'Zambian Kwacha',
    ];

    /**
     * Install and configure bagisto.
     */
    public function handle(): void
    {
        $hasExistingEnv = file_exists(base_path('.env'));

        if (! $hasExistingEnv) {
            $this->components->info('Creating the environment configuration file.');

            File::copy('.env.example', '.env');
        } else {
            $this->components->info('Great! your environment configuration file already exists.');
        }

        ! $this->option('skip-env-check')
            ? $this->askDetailsAndUpdateEnv()
            : $this->components->warn('Skipping environment check. This will assume that the `.env` file is already configured. If not, please create it manually.');

        if (! $hasExistingEnv) {
            $this->updateEnvVariables();

            $this->reconnectDatabase();

            $this->loadEnvConfigs();
        } else {
            $this->updateEnvVariables();

            $this->loadEnvConfigs();
        }

        $this->warn('Step: Generating key...');
        $this->call('key:generate');

        $this->warn('Step: Migrating all tables...');
        $this->call('migrate:fresh');

        $this->warn('Step: Seeding basic data for Bagisto kickstart...');
        app(BagistoDatabaseSeeder::class)->run($this->getSeederConfiguration());
        $this->components->info('Basic data seeded successfully.');

        $this->warn('Step: Linking storage directory...');
        $this->call('storage:link');

        if (! $this->option('skip-admin-creation')) {
            $this->warn('Step: Create admin credentials...');
            $this->askForAdminDetails();
        }

        $this->warn('Step: Clearing cached bootstrap files...');
        $this->call('optimize:clear');

        ComposerEvents::postCreateProject();
    }

    /**
     * Request environment configuration details and set them in the `.env`
     * file to facilitate the migration to our database.
     */
    protected function askDetailsAndUpdateEnv(): void
    {
        try {
            $this->askForApplicationDetails();

            $this->askForDatabaseDetails();
        } catch (\Exception $e) {
            $this->error('Error in updating `.env` file, please create it manually and then run `php artisan migrate` again.');
        }
    }

    /**
     * Ask for application details.
     */
    protected function askForApplicationDetails(): void
    {
        $this->updateTextTypeEnv(
            'APP_NAME',
            'Please enter the application name',
            $this->getEnvVariable('APP_NAME', 'Bagisto')
        );

        $this->updateTextTypeEnv(
            'APP_URL',
            'Please enter the application URL',
            $this->getEnvVariable('APP_URL', 'http://localhost:8000')
        );

        $this->updateChoiceTypeEnv(
            'APP_TIMEZONE',
            'Please select the application timezone',
            $this->getTimezones(),
            true
        );

        $this->updateChoiceTypeEnv(
            'APP_LOCALE',
            'Please select the default application locale',
            $this->locales
        );

        $this->updateChoiceTypeEnv(
            'APP_CURRENCY',
            'Please select the default currency',
            $this->currencies
        );

        $this->updateMultiSelectTypeEnv(
            'APP_ALLOWED_LOCALES',
            'Please choose the allowed locales for your channels',
            $this->locales,
            $this->envDetails['APP_LOCALE']
        );

        $this->updateMultiSelectTypeEnv(
            'APP_ALLOWED_CURRENCIES',
            'Please choose the allowed currencies for your channels',
            $this->currencies,
            $this->envDetails['APP_CURRENCY']
        );
    }

    /**
     * Add the database credentials to the `.env` file.
     *
     * @return mixed
     */
    protected function askForDatabaseDetails()
    {
        $databaseDetails = [
            'DB_CONNECTION' => select(
                label   : 'Please select the database connection',
                options : ['mysql'],
                default : 'mysql',
            ),

            'DB_HOST' => text(
                label    : 'Please enter the database host',
                default  : $this->getEnvVariable('DB_HOST', '127.0.0.1'),
                required : true
            ),

            'DB_PORT' => text(
                label    : 'Please enter the database port',
                default  : $this->getEnvVariable('DB_PORT', '3306'),
                required : true
            ),

            'DB_DATABASE' => text(
                label    : 'Please enter the database name',
                default  : $this->getEnvVariable('DB_DATABASE', ''),
                required : true
            ),

            'DB_PREFIX' => text(
                label   : 'Please enter the database prefix',
                default : $this->getEnvVariable('DB_PREFIX', ''),
                hint    : 'or press enter to continue'
            ),

            'DB_USERNAME' => text(
                label    : 'Please enter your database username',
                default  : $this->getEnvVariable('DB_USERNAME', ''),
                required : true
            ),

            'DB_PASSWORD' => password(
                label    : 'Please enter your database password',
                required : false
            ),
        ];

        if (
            ! $databaseDetails['DB_DATABASE']
            || ! $databaseDetails['DB_USERNAME']
        ) {
            return $this->error('Please enter the database credentials.');
        }

        foreach ($databaseDetails as $key => $value) {
            if ($value) {
                $this->envDetails[$key] = $value;
            }
        }
    }

    /**
     * Create a admin credentials.
     *
     * @return mixed
     */
    protected function askForAdminDetails()
    {
        $adminName = text(
            label    : 'Enter the name of the admin user',
            default  : 'Example',
            required : true
        );

        $adminEmail = text(
            label    : 'Enter the email address of the admin user',
            default  : 'admin@example.com',
            validate : fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address you entered is not valid please try again.',
                default                                     => null
            }
        );

        $adminPassword = text(
            label    : 'Configure the password for the admin user',
            default  : 'admin123',
            required : true,
            validate : function (string $value) {
                if (strlen($value) < 6) {
                    return 'The password must be at least 6 characters.';
                }
            }
        );

        $sampleProduct = select(
            label   : 'Please select if you want some sample products after installation.',
            options : ['true', 'false'],
            default : 'false',
            hint    : 'The action will create products after installation.',
        );

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

            if ($sampleProduct === 'true') {
                $this->warn('Step: Seeding sample product data. Please Wait...');

                app(DatabaseManager::class)->seedSampleProducts($this->getSeederConfiguration());

                $this->components->info('Sample product data seeded successfully.');
            }

            $filePath = storage_path('installed');

            File::put($filePath, 'Bagisto is successfully installed.');

            $this->info('-----------------------------');
            $this->info('Congratulations!');
            $this->info('The installation has been finished and you can now use Bagisto.');
            $this->info('Go to '.$this->getEnvVariable('APP_URL').'/'.$this->getEnvVariable('APP_ADMIN_URL', 'admin').' and authenticate with:');
            $this->info('Email: '.$adminEmail);
            $this->info('Password: '.$adminPassword);
            $this->info('Cheers!');

            Event::dispatch('bagisto.installed');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Method for asking the details of `.env` files.
     */
    protected function updateTextTypeEnv(string $key, string $question, string $defaultValue): void
    {
        $input = text(
            label    : $question,
            default  : $defaultValue,
            required : true
        );

        $this->envDetails[$key] = $input ?: $defaultValue;
    }

    /**
     * Method for asking choice based on the list of options.
     *
     * @return string
     */
    protected function updateChoiceTypeEnv(string $key, string $question, array $choices, bool $useSuggest = false): void
    {
        if ($useSuggest) {
            $choice = suggest(
                label: $question,
                options: $choices,
                default: $this->getEnvVariable($key, '')
            );
        } else {
            $choice = select(
                label: $question,
                options: $choices,
                default: $this->getEnvVariable($key, '')
            );
        }

        $this->envDetails[$key] = $choice;
    }

    /**
     * Method for getting allowed choices based on the list of options.
     */
    protected function updateMultiSelectTypeEnv(string $key, string $question, array $choices, string $defaultChoice)
    {
        $choices = array_merge(['all' => 'All'], $choices);

        $selectedValues = multiselect(
            label: $question,
            options: array_values($choices),
        );

        $selectedChoices = [];

        foreach ($selectedValues as $selectedValue) {
            foreach ($choices as $choiceKey => $value) {
                if ($selectedValue === $value) {
                    $selectedChoices[$choiceKey] = $value;
                    break;
                }
            }
        }

        $selectedChoices = array_key_exists('all', $selectedChoices)
            ? array_values(array_unique(array_merge(
                [$defaultChoice],
                array_diff(array_keys($choices), [$defaultChoice, 'all'])
            )))
            : array_values(array_unique(array_merge(
                [$defaultChoice],
                array_diff(array_keys($selectedChoices), [$defaultChoice])
            )));

        $this->envDetails[$key] = $selectedChoices;
    }

    /**
     * Update the `.env` file with the provided details.
     */
    protected function updateEnvVariables(): void
    {
        foreach ($this->envDetails as $key => $value) {
            if (! in_array($key, $this->fillableEnvVariables)) {
                continue;
            }

            $this->updateEnvVariable($key, $value, Str::startsWith($key, 'DB_'));
        }
    }

    /**
     * Update the single `.env` value.
     */
    protected function updateEnvVariable(string $key, string $value, bool $addQuotes = false): void
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
     * Loaded `.env` configs.
     */
    protected function loadEnvConfigs(): void
    {
        $this->warn('Step: Loading configurations...');

        /**
         * Setting application environment.
         */
        app()['env'] = $this->getEnvVariable('APP_ENV');

        /**
         * Setting application configuration.
         */
        config([
            'app.env'      => $this->getEnvVariable('APP_ENV'),
            'app.name'     => $this->getEnvVariable('APP_NAME'),
            'app.url'      => $this->getEnvVariable('APP_URL'),
            'app.timezone' => $this->getEnvVariable('APP_TIMEZONE'),
            'app.locale'   => $this->getEnvVariable('APP_LOCALE'),
            'app.currency' => $this->getEnvVariable('APP_CURRENCY'),
        ]);

        /**
         * Setting database configurations.
         */
        $databaseConnection = $this->getEnvVariable('DB_CONNECTION');

        config([
            "database.connections.{$databaseConnection}.host"     => $this->getEnvVariable('DB_HOST'),
            "database.connections.{$databaseConnection}.port"     => $this->getEnvVariable('DB_PORT'),
            "database.connections.{$databaseConnection}.database" => $this->getEnvVariable('DB_DATABASE'),
            "database.connections.{$databaseConnection}.username" => $this->getEnvVariable('DB_USERNAME'),
            "database.connections.{$databaseConnection}.password" => $this->getEnvVariable('DB_PASSWORD'),
            "database.connections.{$databaseConnection}.prefix"   => $this->getEnvVariable('DB_PREFIX'),
        ]);

        DB::purge($databaseConnection);

        $this->components->info('Configuration loaded successfully.');
    }

    /**
     * Check key in `.env` file because it will help to find values at runtime.
     */
    protected function getEnvVariable(string $key, $default = null): string|bool
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

        return $default;
    }

    /**
     * Reconnect to the database with new credentials.
     */
    protected function reconnectDatabase(): void
    {
        $connection = $this->envDetails['DB_CONNECTION'] ?? 'mysql';

        config([
            "database.connections.{$connection}.host"     => $this->envDetails['DB_HOST'] ?? '',
            "database.connections.{$connection}.port"     => $this->envDetails['DB_PORT'] ?? '',
            "database.connections.{$connection}.database" => $this->envDetails['DB_DATABASE'] ?? '',
            "database.connections.{$connection}.username" => $this->envDetails['DB_USERNAME'] ?? '',
            "database.connections.{$connection}.password" => $this->envDetails['DB_PASSWORD'] ?? '',
            "database.connections.{$connection}.prefix"   => $this->envDetails['DB_PREFIX'] ?? '',
        ]);

        DB::purge($connection);
        DB::reconnect($connection);

        try {
            DB::connection()->getPdo();

            $this->components->info('Database connection established successfully.');
        } catch (\Exception $e) {
            $this->error('Database connection failed. Please check your credentials.');

            abort(400);
        }
    }

    /**
     * Get sorted list of timezone abbreviations.
     */
    protected function getTimezones(): array
    {
        $timezoneAbbreviations = DateTimeZone::listAbbreviations();

        $timezones = [];

        foreach ($timezoneAbbreviations as $zones) {
            foreach ($zones as $zone) {
                if (! empty($zone['timezone_id'])) {
                    $timezones[$zone['timezone_id']] = $zone['timezone_id'];
                }
            }
        }

        asort($timezones);

        return $timezones;
    }

    /**
     * Get the seeder configuration.
     */
    protected function getSeederConfiguration(): array
    {
        return [
            'default_locale'     => $this->envDetails['APP_LOCALE'] ?? $this->getEnvVariable('APP_LOCALE', 'en'),
            'allowed_locales'    => $this->envDetails['APP_ALLOWED_LOCALES'] ?? [$this->getEnvVariable('APP_LOCALE', 'en')],
            'default_currency'   => $this->envDetails['APP_CURRENCY'] ?? $this->getEnvVariable('APP_CURRENCY', 'USD'),
            'allowed_currencies' => $this->envDetails['APP_ALLOWED_CURRENCIES'] ?? [$this->getEnvVariable('APP_CURRENCY', 'USD')],
        ];
    }
}
