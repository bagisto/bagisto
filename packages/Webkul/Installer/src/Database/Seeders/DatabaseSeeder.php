<?php

namespace Webkul\Installer\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\Attribute\DatabaseSeeder as AttributeSeeder;
use Webkul\Installer\Database\Seeders\Category\DatabaseSeeder as CategorySeeder;
use Webkul\Installer\Database\Seeders\CMS\DatabaseSeeder as CMSSeeder;
use Webkul\Installer\Database\Seeders\Core\DatabaseSeeder as CoreSeeder;
use Webkul\Installer\Database\Seeders\Customer\DatabaseSeeder as CustomerSeeder;
use Webkul\Installer\Database\Seeders\Inventory\DatabaseSeeder as InventorySeeder;
use Webkul\Installer\Database\Seeders\RMA\DatabaseSeeder as RMASeeder;
use Webkul\Installer\Database\Seeders\Shop\ThemeCustomizationTableSeeder as ShopSeeder;
use Webkul\Installer\Database\Seeders\SocialLogin\DatabaseSeeder as SocialLoginSeeder;
use Webkul\Installer\Database\Seeders\User\DatabaseSeeder as UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * FK-safe execution order:
     *
     * Level 0 - No FK dependencies.
     *   1. Attributes  - Attributes, options, families, and group mappings.
     *   2. Customers   - Customer groups.
     *   3. RMA         - Reasons, statuses, and rules.
     *
     * Level 1 - Prerequisites for channels.
     *   4. Categories  - Required by channels.root_category_id.
     *   5. Inventory   - Required by channel_inventory_sources.inventory_source_id.
     *   6. Core        - Locales, currencies, countries, states, channels, and config.
     *
     * Level 2 - Depends on channels and roles.
     *   7. CMS         - CMS page channels require channels.id.
     *   8. SocialLogin - Inserts into core_config (needs sequence sync after Core).
     *   9. Shop/Theme  - Theme customizations require channels.id.
     *  10. Users       - Roles and admin users.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        /*
         * Level 0: No external FK dependencies.
         */
        $this->call(AttributeSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CustomerSeeder::class, false, ['parameters' => $parameters]);
        $this->call(RMASeeder::class, false, ['parameters' => $parameters]);

        /*
         * Level 1: Prerequisites for channels.
         * Categories and inventory sources must exist before Core creates channels.
         */
        $this->call(CategorySeeder::class, false, ['parameters' => $parameters]);
        $this->call(InventorySeeder::class, false, ['parameters' => $parameters]);
        $this->call(CoreSeeder::class, false, ['parameters' => $parameters]);

        /*
         * Level 2: Depends on channels and roles being present.
         */
        $this->call(CMSSeeder::class, false, ['parameters' => $parameters]);
        $this->call(SocialLoginSeeder::class, false, ['parameters' => $parameters]);
        $this->call(ShopSeeder::class, false, ['parameters' => $parameters]);
        $this->call(UserSeeder::class, false, ['parameters' => $parameters]);
    }
}
