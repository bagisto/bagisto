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
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('channels')->delete();

        DB::table('locales')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $key => $locale) {
            $logoPath = null;

            if (file_exists(base_path(self::BASE_PATH.$locale.'.png'))) {
                $logoPath = Storage::putFile('locales', new File(base_path(self::BASE_PATH.$locale.'.png')));
            }

            DB::table('locales')->insert([
                [
                    'id'        => $key + 1,
                    'code'      => $locale,
                    'name'      => trans('installer::app.seeders.core.locales.'.$locale, [], $defaultLocale),
                    'direction' => in_array($locale, ['ar', 'fa', 'he']) ? 'rtl' : 'ltr',
                    'logo_path' => $logoPath,
                ],
            ]);
        }
    }
}
