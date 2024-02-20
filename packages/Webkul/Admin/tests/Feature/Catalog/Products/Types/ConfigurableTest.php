<?php

use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should fail the validation with errors when certain inputs are not provided when store in configurable product', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku')
        ->assertUnprocessable();
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

    foreach ($attributes as $attributeKey => $value) {
        $response
            ->assertJsonPath('data.attributes.'.$attributeKey.'.id', $value->id)
            ->assertJsonPath('data.attributes.'.$attributeKey.'.code', $value->code);

        foreach ($value->options as $optionKey => $option) {
            $response
                ->assertJsonPath('data.attributes.'.$attributeKey.'.options.'.$optionKey.'.id', $option->id)
                ->assertJsonPath('data.attributes.'.$attributeKey.'.options.'.$optionKey.'.name', $option->admin_name);
        }
    }
});

it('should return the edit page of configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Assert
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

it('should fail the validation with errors when certain inputs are not provided when update in configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertUnprocessable();
});

it('should fail the validation with errors if certain data is not provided correctly in configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'visible_individually' => $unProcessAble = fake()->word(),
        'status'               => $unProcessAble,
        'guest_checkout'       => $unProcessAble,
        'new'                  => $unProcessAble,
        'featured'             => $unProcessAble,
    ])
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured')
        ->assertUnprocessable();
});

it('should update the configurable product', function () {
    // Arrange
    $product = (new ProductFaker())->getConfigurableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
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

    $this->assertModelWise([
        Product::class => [
            [
                'id'                  => $product->id,
                'type'                => $product->type,
                'sku'                 => $product->sku,
                'attribute_family_id' => 1,
                'parent_id'           => null,
                'additional'          => null,
            ],
        ],

        ProductFlat::class => [
            [
                'product_id'        => $product->id,
                'type'              => 'configurable',
                'sku'               => $product->sku,
                'short_description' => $shortDescription,
                'description'       => $description,
                'name'              => $name,
                'price'             => $price,
                'weight'            => $weight,
            ],
        ],
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

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
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
        $this->assertModelWise([
            ProductFlat::class => [
                [
                    'product_id' => $productId,
                    'type'       => 'simple',
                    'sku'        => $variant['sku'],
                    'name'       => $variant['name'],
                    'price'      => $variant['price'],
                    'weight'     => $variant['weight'],
                    'locale'     => $locale,
                    'channel'    => $channel,
                ],
            ],
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
