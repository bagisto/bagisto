<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Concerns\SyncsPostgresSequences;

class CurrencyTableSeeder extends Seeder
{
    use SyncsPostgresSequences;

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
        'VES' => 'Bs.F',
        'VND' => '₫',
        'XAF' => 'FCFA',
        'XOF' => 'CFA',
        'ZAR' => 'R',
        'ZMW' => 'ZK',
    ];

    /**
     * Currency decimal digits per ISO 4217 minor units.
     *
     * Only currencies that differ from the default of 2 are listed here; every
     * other currency falls back to 2. These values drive amount formatting for
     * payment gateways that charge in the currency's smallest unit (e.g. Stripe).
     *
     * @var array
     */
    protected $currencyDecimals = [
        // Zero-decimal currencies.
        'CLP' => 0,
        'JPY' => 0,
        'KRW' => 0,
        'PYG' => 0,
        'VND' => 0,
        'XAF' => 0,
        'XOF' => 0,

        // Three-decimal currencies.
        'BHD' => 3,
        'JOD' => 3,
        'KWD' => 3,
        'LYD' => 3,
        'OMR' => 3,
        'TND' => 3,
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
                    'id' => $key + 1,
                    'code' => $currency,
                    'name' => trans('installer::app.seeders.core.currencies.'.$currency, [], $defaultLocale),
                    'symbol' => $this->currencySymbols[$currency] ?? '',
                    'decimal' => $this->currencyDecimals[$currency] ?? 2,
                ],
            ]);
        }

        $this->syncPostgresSequences(['currencies']);
    }
}
