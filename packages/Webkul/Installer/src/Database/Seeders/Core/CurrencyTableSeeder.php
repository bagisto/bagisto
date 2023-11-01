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

    public function run()
    {
        DB::table('channels')->delete();

        DB::table('currencies')->delete();

        $currencyCode = config('app.currency') ?? 'USD';

        DB::table('currencies')->insert([
            [
                'id'     => 1,
                'code'   => $currencyCode,
                'name'   => trans('installer::app.seeders.core.currencies.' . $currencyCode),
                'symbol' => $this->currencySymbols[$currencyCode],
            ],
        ]);
    }
}
