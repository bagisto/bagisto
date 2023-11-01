<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        $localeCode = config('app.locale') ?? 'en';

        DB::table('locales')->insert([
            [
                'id'        => 1,
                'code'      => $localeCode,
                'name'      => trans('installer::app.seeders.core.locales.' . $localeCode),
                'direction' => in_array($localeCode, ['ar', 'fa', 'he']) ? 'RTL' : 'LTR',
                'logo_path' => 'locales/' . $localeCode . '.png',
            ],
        ]);
    }
}
