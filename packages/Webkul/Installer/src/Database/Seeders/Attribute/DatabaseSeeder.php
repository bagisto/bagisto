<?php

namespace Webkul\Installer\Database\Seeders\Attribute;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * FK-safe order:
     *
     * 1. Attributes - No FK dependencies. Translations reference attributes (self-contained).
     * 2. Options    - Depends on attributes (attribute_options.attribute_id → attributes.id).
     * 3. Families   - No FK dependencies.
     * 4. Groups     - Depends on families (attribute_groups.attribute_family_id → attribute_families.id)
     *                 and attributes (attribute_group_mappings.attribute_id → attributes.id).
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(AttributeTableSeeder::class, false, ['parameters' => $parameters]);

        $this->call(AttributeOptionTableSeeder::class, false, ['parameters' => $parameters]);

        $this->call(AttributeFamilyTableSeeder::class, false, ['parameters' => $parameters]);

        $this->call(AttributeGroupTableSeeder::class, false, ['parameters' => $parameters]);
    }
}
