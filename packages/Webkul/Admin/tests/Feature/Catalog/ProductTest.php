<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\Attribute;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Repositories\ProductAttributeValueRepository;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Clean up all the records.
     */
    ProductModel::query()->delete();
    Attribute::query()->whereNotBetween('id', [1, 28])->delete();
});

it('it should return the product index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should return the create page of simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'simple',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->uuid(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'type' => 'simple',
        'sku'  => $sku,
        'id'   => $productId,
    ]);
});

it('should return the edit page of simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    $this->get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should update the simple product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => $shortDescription = fake()->sentence(),
        'description'       => $description = fake()->paragraph(),
        'name'              => $name = fake()->words(3, true),
        'price'             => $price = fake()->randomFloat(2, 1, 1000),
        'weight'            => $weight = fake()->numberBetween(0, 100),
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'  => $product->sku,
        'type' => $product->type,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'              => $product->url_key,
        'type'                 => 'simple',
        'name'                 => $name,
        'short_description'    => $shortDescription,
        'description'          => $description,
        'price'                => $price,
        'weight'               => $weight,
        'locale'               => $locale,
        'product_id'           => $product->id,
        'channel'              => $channel,
    ]);
});

it('should return the create page of configurable product', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'configurable',
        'attribute_family_id' => 1,
        'sku'                 => fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.attributes.0.id', 23)
        ->assertJsonPath('data.attributes.0.code', 'color')
        ->assertJsonPath('data.attributes.0.options.0.id', 1)
        ->assertJsonPath('data.attributes.0.options.0.name', 'Red')
        ->assertJsonPath('data.attributes.1.id', 24)
        ->assertJsonPath('data.attributes.1.code', 'size')
        ->assertJsonPath('data.attributes.1.options.0.id', 6)
        ->assertJsonPath('data.attributes.1.options.0.name', 'S');
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

    foreach ($product->variants as $variant) {
        $variants[$variant->id] = [
            'sku'         => $variant->sku,
            'name'        => $variant->name,
            'price'       => $variant->price,
            'weight'      => $variant->price,
            'status'      => $variant->status,
            'color'       => $variant->color,
            'size'        => $variant->size,
            'inventories' => [
                1 => rand(1111, 9999),
            ],
        ];
    }

    $this->putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
        'variants'          => $variants,
        'short_description' => fake()->sentence(),
        'description'       => fake()->paragraph(),
        'name'              => fake()->words(3, true),
        'price'             => fake()->randomFloat(2, 1, 1000),
        'weight'            => fake()->numberBetween(0, 100),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'                 => $product->sku,
        'type'                => $product->type,
        'attribute_family_id' => 1,
        'parent_id'           => null,
        'additional'          => null,
    ]);

    foreach ($product->variants as $variant) {
        $variant->refresh();

        $this->assertDatabaseHas('product_flat', [
            'sku'                  => $variant->sku,
            'product_id'           => $variant->id,
            'product_number'       => null,
            'new'                  => null,
            'featured'             => null,
            'status'               => 1,
            'url_key'              => $variant->url_key,
            'type'                 => 'simple',
            'name'                 => $variant->name,
            'short_description'    => $variant->short_description,
            'description'          => $variant->description,
            'price'                => $variant->price,
            'weight'               => $variant->weight,
            'locale'               => $locale,
            'channel'              => $channel,
            'attribute_family_id'  => 1,
            'visible_individually' => 0,
        ]);
    }
});

it('should return the create page of virtual product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'virtual',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'type' => 'virtual',
        'sku'  => $sku,
        'id'   => $productId,
    ]);
});

it('should return the edit page of virtual product', function () {
    // Arrange
    $product = (new ProductFaker())->getVirtualProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    $this->get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should update the virtual product', function () {
    // Arrange
    $product = (new ProductFaker())->getVirtualProductFactory()->create();

    // Act and Asssert
    $this->loginAsAdmin();

    $this->putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => $shortDescription = fake()->sentence(),
        'description'       => $description = fake()->paragraph(),
        'name'              => $name = fake()->words(3, true),
        'price'             => $price = fake()->randomFloat(2, 1, 1000),
        'weight'            => $weight = fake()->numberBetween(0, 100),
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'  => $product->sku,
        'type' => $product->type,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'              => $product->url_key,
        'type'                 => $product->type,
        'name'                 => $name,
        'short_description'    => $shortDescription,
        'description'          => $description,
        'price'                => $price,
        'weight'               => $weight,
        'locale'               => $locale,
        'product_id'           => $product->id,
        'channel'              => $channel,
    ]);
});

it('should return the create page of grouped product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'grouped',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'type' => 'grouped',
        'sku'  => $sku,
        'id'   => $productId,
    ]);
});

it('should return the grouped edit page', function () {
    // Arrange
    $products = (new ProductFaker())->getSimpleProductFactory()->count(5)->create();

    foreach ($products as $key => $product) {
        $links['link_' . $key] = [
            'associated_product_id' => $product->id,
            'sort_order'            => $key,
            'qty'                   => rand(10, 100),
        ];
    }

    $product = (new ProductFaker())->getSimpleProductFactory()->create([
        'type' => 'grouped',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.edit', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => fake()->sentence(),
        'description'       => fake()->paragraph(),
        'name'              => fake()->words(3, true),
        'price'             => fake()->randomFloat(2, 1, 1000),
        'weight'            => fake()->numberBetween(0, 100),
        'channel'           => core()->getCurrentChannelCode(),
        'locale'            => app()->getLocale(),
        'links'             => $links ?? [],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.edit.title'))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'))
        ->assertSeeText($product->url_key)
        ->assertSeeText($product->name)
        ->assertSeeText($product->short_description)
        ->assertSeeText($product->description);
});

it('should update the grouped product', function () {
    // Arrange
    $products = (new ProductFaker())->getSimpleProductFactory()->count(5)->create();

    foreach ($products as $key => $product) {
        $links['link_' . $key] = [
            'associated_product_id' => $product->id,
            'sort_order'            => $key,
            'qty'                   => rand(10, 100),
        ];
    }

    $product = (new ProductFaker())->getSimpleProductFactory()->create([
        'type' => 'grouped',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $product->sku,
        'url_key'           => $product->url_key,
        'short_description' => $shortDescription = fake()->sentence(),
        'description'       => $description = fake()->paragraph(),
        'name'              => $name = fake()->words(3, true),
        'price'             => $price = fake()->randomFloat(2, 1, 1000),
        'weight'            => $weight = fake()->numberBetween(0, 100),
        'channel'           => $channel = core()->getCurrentChannelCode(),
        'locale'            => $locale = app()->getLocale(),
        'links'             => $links ?? [],
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'                 => $product->sku,
        'type'                => $product->type,
        'attribute_family_id' => 1,
        'parent_id'           => null,
        'additional'          => null,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'              => $product->url_key,
        'type'                 => 'grouped',
        'name'                 => $name,
        'short_description'    => $shortDescription,
        'description'          => $description,
        'price'                => $price,
        'weight'               => $weight,
        'locale'               => $locale,
        'product_id'           => $product->id,
        'channel'              => $channel,
    ]);

    foreach ($products as $product) {
        $this->assertDatabaseHas('product_flat', [
            'url_key'              => $product->url_key,
            'type'                 => 'simple',
            'name'                 => $product->name,
            'short_description'    => $product->short_description,
            'description'          => $product->description,
            'price'                => $product->price,
            'weight'               => $product->weight,
            'locale'               => $locale,
            'product_id'           => $product->id,
            'channel'              => $channel,
        ]);
    }
});

it('should return the create page of downloadable product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'downloadable',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'type' => 'downloadable',
        'sku'  => $sku,
        'id'   => $productId,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'           => $product->url_key,
        'type'              => 'simple',
        'name'              => $product->name,
        'short_description' => $product->short_description,
        'description'       => $product->description,
        'price'             => $product->price,
        'weight'            => $product->weight,
        'locale'            => app()->getLocale(),
        'product_id'        => $product->id,
        'channel'           => core()->getCurrentChannelCode(),
    ]);
});

it('should return the edit page of downloadable product', function () {
    // Arrange
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

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

it('should update the downloadable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            26 => 'guest_checkout',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],

            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    $file = UploadedFile::fake()->create('ProductImageExampleForUpload.jpg');

    // Act and Asssert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'                => $product->sku,
        'url_key'            => $product->url_key,
        'short_description'  => $shortDescription = fake()->sentence(),
        'description'        => $description = fake()->paragraph(),
        'name'               => $name = fake()->words(3, true),
        'price'              => $price = fake()->randomFloat(2, 1, 1000),
        'weight'             => $weight = fake()->numberBetween(0, 100),
        'channel'            => $channel = core()->getCurrentChannelCode(),
        'locale'             => $locale = app()->getLocale(),
        'downloadable_links' => [
            'link_0' => [
                'en' => [
                    'title' => fake()->title,
                ],
                'price'            => rand(10, 250),
                'downloads'        => '1',
                'sort_order'       => '0',
                'type'             => 'file',
                'file'             => $file,
                'file_name'        => $file->getClientOriginalName(),
                'sample_type'      => 'url',
                'sample_url'       => fake()->url(),
            ],

            'link_1' => [
                'en' => [
                    'title' => fake()->title,
                ],
                'price'            => rand(10, 250),
                'downloads'        => '1',
                'sort_order'       => '0',
                'type'             => 'file',
                'file'             => $file,
                'file_name'        => $file->getClientOriginalName(),
                'sample_type'      => 'file',
                'sample_file'      => $file,
                'sample_file_name' => $file->getClientOriginalName(),
            ],
        ],

        'downloadable_samples' => [
            'sample_0' => [
                'title'      => $sample0Title = fake()->title(),
                'sort_order' => '0',
                'type'       => 'file',
                'file'       => $file,
                'file_name'  => $file->getClientOriginalName(),
            ],

            'sample_1' => [
                'title'      => fake()->title(),
                'sort_order' => '1',
                'type'       => 'url',
                'url'        => fake()->url(),
            ],
        ],
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'  => $product->sku,
        'type' => $product->type,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'              => $product->url_key,
        'type'                 => 'downloadable',
        'name'                 => $name,
        'short_description'    => $shortDescription,
        'description'          => $description,
        'price'                => $price,
        'weight'               => $weight,
        'locale'               => $locale,
        'product_id'           => $product->id,
        'channel'              => $channel,
    ]);
});

it('should return the create page of bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'bundle',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'type' => 'bundle',
        'sku'  => $sku,
        'id'   => $productId,
    ]);
});

it('should return the edit page of bundle product', function () {
    // Arrange
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

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

it('should update the bundle product', function () {
    // Arrange
    $products = (new ProductFaker())->getSimpleProductFactory()->count(5)->create();

    foreach ($products as $key => $product) {
        $options['option_' . $key] = [
            'en' => [
                'label' => fake()->title(),
            ],
            'type'        => fake()->randomElement(['select', 'radio', 'checkbox', 'multiselect']),
            'is_required' => '1',
            'sort_order'  => $key,
            'products'    => [
                'product_' . $key => [
                    'product_id' => $product->id,
                    'sort_order' => $key,
                    'qty'        => rand(10, 100),
                ],
            ],
        ];
    }

    $product = (new ProductFaker())->getSimpleProductFactory()->create([
        'type' => 'bundle',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'                  => $product->sku,
        'url_key'              => $product->url_key,
        'short_description'    => $shortDescription = fake()->sentence(),
        'description'          => $description = fake()->paragraph(),
        'name'                 => $name = fake()->words(3, true),
        'price'                => $price = fake()->randomFloat(2, 1, 1000),
        'weight'               => $weight = fake()->numberBetween(0, 100),
        'channel'              => $channel = core()->getCurrentChannelCode(),
        'locale'               => $locale = app()->getLocale(),
        'bundle_options'       => $options,
        'new'                  => '1',
        'featured'             => '1',
        'visible_individually' => '1',
        'status'               => '1',
        'guest_checkout'       => '1',
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'                 => $product->sku,
        'type'                => $product->type,
        'id'                  => $product->id,
        'attribute_family_id' => 1,
        'parent_id'           => null,
        'additional'          => null,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key'              => $product->url_key,
        'type'                 => 'bundle',
        'name'                 => $name,
        'short_description'    => $shortDescription,
        'description'          => $description,
        'price'                => $price,
        'weight'               => $weight,
        'locale'               => $locale,
        'product_id'           => $product->id,
        'channel'              => $channel,
    ]);

    foreach ($products as $product) {
        $this->assertDatabaseHas('product_flat', [
            'url_key'              => $product->url_key,
            'type'                 => 'simple',
            'name'                 => $product->name,
            'short_description'    => $product->short_description,
            'description'          => $product->description,
            'price'                => $product->price,
            'weight'               => $product->weight,
            'locale'               => $locale,
            'product_id'           => $product->id,
            'channel'              => $channel,
        ]);
    }
});

it('should copy the existing product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.copy', $product->id))
        ->assertRedirect(route('admin.catalog.products.edit', $productId = $product->id + 1))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'id' => $productId,
    ]);
});

it('should delete a product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('should upload link the product upload link', function () {
    // Arrange
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    $response = postJson(route('admin.catalog.products.upload_link', $product->id), [
        'file' => $file = UploadedFile::fake()->create(fake()->word() . '.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->getClientOriginalName());

    if (Storage::disk('private')->exists($response['file'])) {
        Storage::disk('private')->delete($response['file']);
    }
});

it('should upload the sample file', function () {
    // Arrange
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    $response = postJson(route('admin.catalog.products.upload_sample', $product->id), [
        'file' => $file = UploadedFile::fake()->create(fake()->word() . '.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->name);

    if (Storage::disk('public')->exists($response['file'])) {
        Storage::disk('public')->delete($response['file']);
    }
});

it('should perform the mass action forn update status for products', function () {
    // Arrange
    $products = (new ProductFaker())->getSimpleProductFactory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_update'), [
        'indices' => $products->pluck('id')->toArray(),
        'value'   => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-update-success'));

    foreach ($products as $product) {
        $this->assertDatabaseHas('product_flat', [
            'status'     => 1,
            'product_id' => $product->id,
        ]);
    }
});

it('should perform the mass action for delete for products', function () {
    // Arrange
    $products = (new ProductFaker())->getSimpleProductFactory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.mass_delete'), [
        'indices' => $products->pluck('id')->toArray(),
        'value'   => 1,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.index.datagrid.mass-delete-success'));

    foreach ($products as $product) {
        $this->assertDatabaseMissing('product_flat', [
            'status'     => 1,
            'product_id' => $product->id,
        ]);
    }
});

it('should search the product', function () {
    // Arrange
    $product = (new ProductFaker)->getSimpleProductFactory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.search', [
        'query' => $product[0]->name,
    ]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product[0]->id)
        ->assertJsonPath('data.0.name', $product[0]->name)
        ->assertJsonPath('data.0.sku', $product[0]->sku);
});

it('should download the product which is downloadable', function () {
    // Arrange
    $attribute = Attribute::factory()->create([
        'type' => 'file',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            $attribute->id => $attribute->code,
        ],

        'attribute_value' => [
            $attribute->code => [
                'text_value' => $file = UploadedFile::fake()->create(fake()->word() . '.pdf', 100),
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    $fileName = $file->store('product/' . $product->id);

    $atttributeValues = app(ProductAttributeValueRepository::class)->findOneWhere([
        'product_id'   => $product->id,
        'attribute_id' => $attribute->id,
    ]);

    $atttributeValues->text_value = $fileName;
    $atttributeValues->save();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.file.download', [$product->id, $attribute->id]))
        ->assertOk();

    Storage::assertExists($fileName);
});
