<?php

namespace Webkul\Attribute\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Attribute\Models\Attribute;

class AttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attribute::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'admin_name' => $this->faker->word(),
            'code' => $this->faker->unique()->lexify('attr_??????????'),
            'type' => $this->faker->randomElement([
                'text',
                'textarea',
                'price',
                'boolean',
                'select',
                'multiselect',
                'datetime',
                'date',
                'image',
                'file',
                'checkbox',
            ]),
            'validation' => '',
            'position' => $this->faker->randomDigit(),
            'is_required' => false,
            'is_unique' => false,
            'value_per_locale' => false,
            'value_per_channel' => false,
            'is_filterable' => false,
            'is_configurable' => false,
            'is_user_defined' => true,
            'is_visible_on_front' => true,
            'swatch_type' => null,
        ];
    }

    /**
     * Set the attribute's validation to numeric.
     */
    public function validationNumeric(): static
    {
        return $this->state(fn () => ['validation' => 'numeric']);
    }

    /**
     * Set the attribute's validation to email.
     */
    public function validationEmail(): static
    {
        return $this->state(fn () => ['validation' => 'email']);
    }

    /**
     * Set the attribute's validation to decimal.
     */
    public function validationDecimal(): static
    {
        return $this->state(fn () => ['validation' => 'decimal']);
    }

    /**
     * Set the attribute's validation to URL.
     */
    public function validationUrl(): static
    {
        return $this->state(fn () => ['validation' => 'url']);
    }

    /**
     * Mark the attribute as required.
     */
    public function required(): static
    {
        return $this->state(fn () => ['is_required' => true]);
    }

    /**
     * Mark the attribute as unique.
     */
    public function unique(): static
    {
        return $this->state(fn () => ['is_unique' => true]);
    }

    /**
     * Mark the attribute as filterable.
     */
    public function filterable(): static
    {
        return $this->state(fn () => ['is_filterable' => true]);
    }

    /**
     * Mark the attribute as configurable.
     */
    public function configurable(): static
    {
        return $this->state(fn () => ['is_configurable' => true]);
    }
}
