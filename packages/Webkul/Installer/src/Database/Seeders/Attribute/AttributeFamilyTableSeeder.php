<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Concerns\SyncsPostgresSequences;

class AttributeFamilyTableSeeder extends Seeder
{
    use SyncsPostgresSequences;

    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('attribute_families')->delete();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('attribute_families')->insert([
            [
                'id' => 1,
                'code' => 'default',
                'name' => trans('installer::app.seeders.attribute.attribute-families.default', [], $defaultLocale),
                'status' => 0,
                'is_user_defined' => 1,
            ],
        ]);

        $this->syncPostgresSequences(['attribute_families']);
    }
}
