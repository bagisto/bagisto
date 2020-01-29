<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use DB;

class CountryStateTranslationSeeder extends Seeder
{
    public function run()
    {

        foreach (['ar', 'fa', 'pt_BR'] as $code) {
            DB::table('country_translations')->where('locale', $code)->delete();

            DB::table('country_state_translations')->where('locale', $code)->delete();

            $countryTranslations = json_decode(file_get_contents(__DIR__ . '/../../Data/country_state_translation/countries_' . $code . '.json'), true);

            data_fill($countryTranslations, '*.locale', $code);

            $stateTranslations = json_decode(file_get_contents(__DIR__ . '/../../Data/country_state_translation/states_' . $code . '.json'), true);

            data_fill($stateTranslations, '*.locale', $code);

            DB::table('country_translations')->insert($countryTranslations);
            DB::table('country_state_translations')->insert($stateTranslations);
        }

    }
}