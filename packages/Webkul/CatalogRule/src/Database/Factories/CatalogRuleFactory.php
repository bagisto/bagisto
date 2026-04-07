<?php

namespace Webkul\CatalogRule\Database\Factories;

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
            'starts_from' => $this->faker->dateTimeThisMonth,
            'ends_till' => $this->faker->dateTimeBetween($startsFrom, $endsTill),
            'status' => $this->faker->boolean(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'action_type' => 'by_percent',
            'discount_amount' => rand(1, 50),
        ];
    }

    /**
     * Sync channels, customer groups, and trigger the catalog rule indexer.
     *
     * Call this after creating a rule that needs its price indices populated.
     * The event must fire AFTER channels and customer groups are synced,
     * otherwise the indexing job finds no relationships and skips the product.
     */
    public function withIndex(array $channelIds = [1], array $customerGroupIds = [1, 2, 3]): static
    {
        return $this->afterCreating(function (CatalogRule $catalogRule) use ($channelIds, $customerGroupIds) {
            $catalogRule->channels()->sync($channelIds);
            $catalogRule->customer_groups()->sync($customerGroupIds);

            Event::dispatch('promotions.catalog_rule.update.after', $catalogRule);
        });
    }
}
