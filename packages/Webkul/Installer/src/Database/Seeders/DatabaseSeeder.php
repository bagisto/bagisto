<?php

namespace Webkul\Installer\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\Attribute\DatabaseSeeder as AttributeSeeder;
use Webkul\Installer\Database\Seeders\Category\DatabaseSeeder as CategorySeeder;
use Webkul\Installer\Database\Seeders\CMS\DatabaseSeeder as CMSSeeder;
use Webkul\Installer\Database\Seeders\Core\DatabaseSeeder as CoreSeeder;
use Webkul\Installer\Database\Seeders\Customer\DatabaseSeeder as CustomerSeeder;
use Webkul\Installer\Database\Seeders\Inventory\DatabaseSeeder as InventorySeeder;
use Webkul\Installer\Database\Seeders\Shop\ThemeCustomizationTableSeeder as ShopSeeder;
use Webkul\Installer\Database\Seeders\SocialLogin\DatabaseSeeder as SocialLoginSeeder;
use Webkul\Installer\Database\Seeders\User\DatabaseSeeder as UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(AttributeSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CategorySeeder::class, false, ['parameters' => $parameters]);
        $this->call(CoreSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CustomerSeeder::class, false, ['parameters' => $parameters]);
        $this->call(CMSSeeder::class, false, ['parameters' => $parameters]);
        $this->call(InventorySeeder::class, false, ['parameters' => $parameters]);
        $this->call(SocialLoginSeeder::class, false, ['parameters' => $parameters]);
        $this->call(ShopSeeder::class, false, ['parameters' => $parameters]);
        $this->call(UserSeeder::class, false, ['parameters' => $parameters]);
    }
}
