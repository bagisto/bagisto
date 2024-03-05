<?php

namespace Webkul\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;
use Webkul\Inventory\Models\InventorySource;

class ChannelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Channel::class;

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->hasAttached(Currency::inRandomOrder()->limit(1)->get())
            ->hasAttached(Locale::inRandomOrder()->limit(1)->get())
            ->hasAttached(InventorySource::inRandomOrder()->limit(1)->get(), [], 'inventory_sources')
            ->hasTranslations();
    }

    /**
     * Define the model's default state.
     *
     * @throws \JsonException
     */
    public function definition(): array
    {
        return [
            'code'              => $code = $this->faker->unique()->word(),
            'theme'             => $code,
            'hostname'          => 'http://'.$this->faker->ipv4(),
            'root_category_id'  => 1,
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
        ];
    }
}
