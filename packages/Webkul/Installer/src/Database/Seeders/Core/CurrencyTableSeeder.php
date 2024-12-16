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
        'AED' => 'د.إ',
        'ARS' => '$',
        'AUD' => 'A$',
        'BHD' => '.د.ب',
        'BDT' => '৳',
        'BHD' => 'BHD',
        'BRL' => 'R$',
        'CAD' => 'C$',
        'CHF' => 'CHF',
        'CLP' => '$',
        'CNY' => '¥',
        'COP' => '$',
        'CZK' => 'Kč',
        'DKK' => 'kr',
        'DZD' => 'د.ج',
        'EGP' => 'E£',
        'EUR' => '€',
        'FJD' => 'FJ$',
        'GBP' => '£',
        'HKD' => 'HK$',
        'HUF' => 'Ft',
        'IDR' => 'Rp',
        'ILS' => '₪',
        'INR' => '₹',
        'JOD' => 'د.ا',
        'JPY' => '¥',
        'KRW' => '₩',
        'KWD' => 'د.ك',
        'KZT' => '₸',
        'LBP' => 'ل.ل',
        'LKR' => '₨',
        'LYD' => 'ل.د',
        'MAD' => 'د.م.',
        'MUR' => '₨',
        'MXN' => '$',
        'MYR' => 'RM',
        'NGN' => '₦',
        'NOK' => 'kr',
        'NPR' => '₨',
        'NZD' => 'NZ$',
        'OMR' => '﷼',
        'PAB' => 'B/.',
        'PEN' => 'S/',
        'PHP' => '₱',
        'PKR' => '₨',
        'PLN' => 'zł',
        'PYG' => '₲',
        'QAR' => '﷼',
        'RON' => 'lei',
        'RUB' => '₽',
        'SAR' => '﷼',
        'SEK' => 'kr',
        'SGD' => 'S$',
        'THB' => '฿',
        'TND' => 'د.ت',
        'TRY' => '₺',
        'TWD' => 'NT$',
        'UAH' => '₴',
        'USD' => '$',
        'UZS' => 'сўм',
        'VEF' => 'Bs.F',
        'VND' => '₫',
        'XAF' => 'FCFA',
        'XOF' => 'CFA',
        'ZAR' => 'R',
        'ZMW' => 'ZK',
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
