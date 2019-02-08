<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CurrenySymbolTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('currency_code_symbol')->delete();

        $currencies = json_decode(file_get_contents(__DIR__ . '/../../Data/currency_symbols.json'), true);

        DB::table('currency_code_symbol')->insert($currencies);
    }
}