<?php

namespace Webkul\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Models\CatalogRule;

class CatalogRuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CatalogRule::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $startsFrom = $this->faker->dateTimeBetween('now', '+30 days');
        $endsTill = $this->faker->dateTimeBetween($startsFrom, $startsFrom->format('Y-m-d').' +30 days');

        return [
            'starts_from'     => $this->faker->dateTimeThisMonth,
            'ends_till'       => $this->faker->dateTimeBetween($startsFrom, $endsTill),
            'status'          => $this->faker->boolean(),
            'name'            => $this->faker->name,
            'description'     => substr($this->faker->paragraph, 0, 50),
            'action_type'     => 'by_percent',
            'discount_amount' => rand(1, 50),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (CatalogRule $catalogRule) {
            Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);
        });
    }
}
