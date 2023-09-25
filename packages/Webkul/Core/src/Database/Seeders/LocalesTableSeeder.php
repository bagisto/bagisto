<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        DB::table('locales')->insert([
            [
                'id'        => 1,
                'code'      => 'en',
                'name'      => 'English',
                'logo_path' => 'locales/en.png',
            ], [
                'id'        => 2,
                'code'      => 'fr',
                'name'      => 'French',
                'logo_path' => 'locales/fr.png',
            ], [
                'id'        => 3,
                'code'      => 'nl',
                'name'      => 'Dutch',
                'logo_path' => 'locales/nl.png',
            ], [
                'id'        => 4,
                'code'      => 'tr',
                'name'      => 'Türkçe',
                'logo_path' => 'locales/tr.png',
            ], [
                'id'        => 5,
                'code'      => 'es',
                'name'      => 'Español',
                'logo_path' => 'locales/es.png',
            ], [
                'id'        => 6,
                'code'      => 'de',
                'name'      => 'German',
                'logo_path' => 'locales/de.png',
            ], [
                'id'        => 7,
                'code'      => 'it',
                'name'      => 'Italian	',
                'logo_path' => 'locales/it.png',
            ], [
                'id'        => 8,
                'code'      => 'ru',
                'name'      => 'Russian',
                'logo_path' => 'locales/ru.png',
            ], [
                'id'        => 9,
                'code'      => 'uk',
                'name'      => 'Ukrainian',
                'logo_path' => 'locales/uk.png',
            ],
        ]);
    }
}
