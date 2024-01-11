<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product as ProductModel;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    /**
     * Clean up all the records.
     */
    ProductModel::query()->delete();
    Attribute::query()->whereNotBetween('id', [1, 28])->delete();
});

it('should return the create page of configurable product', function () {
    // Arrange
    $attributes = AttributeFamily::find($attributeFamily = 1)->configurable_attributes;

    // Act and Assert
    $this->loginAsAdmin();

    $response = postJson(route('admin.catalog.products.store'), [
        'type'                => 'configurable',
        'attribute_family_id' => $attributeFamily,
        'sku'                 => fake()->slug(),
    ])
        ->assertOk();

    foreach ($attributes as $attributekey => $value) {
        $response
            ->assertJsonPath('data.attributes.' . $attributekey . '.id', $value->id)
            ->assertJsonPath('data.attributes.' . $attributekey . '.code', $value->code);

        foreach ($value->options as $optionKey => $option) {
            $response
                ->assertJsonPath('data.attributes.' . $attributekey . '.options.' . $optionKey . '.id', $option->id)
                ->assertJsonPath('data.attributes.' . $attributekey . '.options.' . $optionKey . '.name', $option->admin_name);
        }
    }
});

it('should return the edit page of configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should update the configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    $this->putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'channel'           => core()->getCurrentChannelCode(),
        'locale'            => app()->getLocale(),
        'short_description' => $shortDescription = fake()->sentence(),
        'description'       => $description = fake()->paragraph(),
        'name'              => $name = fake()->words(3, true),
        'price'             => $price = fake()->randomFloat(2, 1, 1000),
        'weight'            => $weight = fake()->numberBetween(0, 100),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'id'                  => $product->id,
        'type'                => $product->type,
        'sku'                 => $product->sku,
        'attribute_family_id' => 1,
        'parent_id'           => null,
        'additional'          => null,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'product_id'        => $product->id,
        'type'              => 'configurable',
        'sku'               => $product->sku,
        'short_description' => $shortDescription,
        'description'       => $description,
        'name'              => $name,
        'price'             => $price,
        'weight'            => $weight,
    ]);
});

it('should update the configurable product variants', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    $attributeOptions = AttributeFamily::find(1)->configurable_attributes
        ->flatMap(function ($attribute) {
            return [
                $attribute->code => collect($attribute->options)->pluck('label')->all(),
            ];
        })->all();

    $variants = [];

    foreach ($product->variants as $variant) {
        $variants[$variant->id] = [
            'sku'         => fake()->uuid(),
            'name'        => fake()->words(3, true),
            'price'       => fake()->randomFloat(2, 1, 1000),
            'weight'      => fake()->numberBetween(0, 100),
            'status'      => fake()->boolean(),
            'color'       => fake()->randomElement($attributeOptions['color']),
            'size'        => fake()->randomElement($attributeOptions['size']),
            'inventories' => [
                1 => rand(1111, 9999),
            ],
        ];
    }

    // Act and Asssert
    $this->loginAsAdmin();

    $this->putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
        'short_description' => $product->short_description,
        'description'       => $product->description,
        'name'              => $product->name,
        'price'             => $product->price,
        'weight'            => $product->weight,
        'variants'          => $variants,
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    foreach ($variants as $productId => $variant) {
        $this->assertDatabaseHas('product_flat', [
            'product_id' => $productId,
            'type'       => 'simple',
            'sku'        => $variant['sku'],
            'name'       => $variant['name'],
            'price'      => $variant['price'],
            'weight'     => $variant['weight'],
            'locale'     => $locale,
            'channel'    => $channel,
        ]);
    }
});

it('should delete a configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);

    foreach ($product->variants as $variant) {
        $this->assertDatabaseMissing('product_flat', [
            'product_id' => $variant->id,
        ]);
    }
});
