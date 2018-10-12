<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Core\Models\Currency;

class CurrencyTableSeeder extends Seeder
{
    public function run()
    {
        $currency = new Currency();
        $locale->code = 'USD';
        $locale->name = 'US Dollar';
        $locale->symbol = '$';
        $locale->save();
    }
}