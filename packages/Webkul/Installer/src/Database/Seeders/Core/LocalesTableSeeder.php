<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LocalesTableSeeder extends Seeder
{
    /**
     * Base path for the images.
     */
    const BASE_PATH = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/locales/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        $localeCode = config('app.locale') ?? 'en';

        $logoPath = null;

        if (file_exists(base_path(self::BASE_PATH . $localeCode . '.png'))) {
            $logoPath = Storage::putFile('locales', new File(base_path(self::BASE_PATH . $localeCode . '.png')));
        }

        DB::table('locales')->insert([
            [
                'id'        => 1,
                'code'      => $localeCode,
                'name'      => trans('installer::app.seeders.core.locales.' . $localeCode),
                'direction' => in_array($localeCode, ['ar', 'fa', 'he']) ? 'RTL' : 'LTR',
                'logo_path' => $logoPath,
            ],
        ]);
    }
}
