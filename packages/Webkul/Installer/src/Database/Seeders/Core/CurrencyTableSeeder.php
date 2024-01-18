<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Currency symbols.
     *
     * @var array
     */
    protected $currencySymbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'RUB' => '₽',
        'UAH' => '₴',
        'INR' => '₹',
        'CNY' => '¥',
        'JPY' => '¥',
        'AED' => 'د.إ',
        'SAR' => '﷼',
        'IRR' => '﷼',
        'AFN' => '؋',
        'TRY' => '₺',
    ];

    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('channels')->delete();

        DB::table('currencies')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $defaultCurrency = $parameters['default_currency'] ?? config('app.currency');

        $currencies = $parameters['allowed_currencies'] ?? [$defaultCurrency];

        foreach ($currencies as $key => $currency) {
            DB::table('currencies')->insert([
                [
                    'id'     => $key + 1,
                    'code'   => $currency,
                    'name'   => trans('installer::app.seeders.core.currencies.'.$currency, [], $defaultLocale),
                    'symbol' => $this->currencySymbols[$currency],
                ],
            ]);
        }
    }
}
