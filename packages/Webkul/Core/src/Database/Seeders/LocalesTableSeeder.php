<?php

namespace Webkul\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Core\Models\Locale;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        $locale = new Locale();
        $locale->code = 'en';
        $locale->name = 'English';
        $locale->save();

        $locale = new Locale();
        $locale->code = 'fr';
        $locale->name = 'French';
        $locale->save();
    }
}