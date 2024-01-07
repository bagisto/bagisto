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

it('it should returns the product index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.catalog.products.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.catalog.products.index.title'))
        ->assertSeeText(trans('admin::app.catalog.products.index.create-btn'));
});

it('should return the create page products', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => $type = fake()->randomElement(['simple', 'configurable', 'virtual', 'downloadable', 'grouped', 'bundle']),
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->uuid(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertDatabaseHas('products', [
        'id'   => $productId,
        'type' => $type,
        'sku'  => $sku,
    ]);
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

it('it should update the existing product', function () {
    // Arrange
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku'               => $sku = fake()->slug(),
        'url_key'           => $urlKey = fake()->url(),
        'name'              => $name = fake()->name(),
        'price'             => rand(1111, 9999),
        'weight'            => rand(10, 50),
        'short_description' => fake()->paragraph(),
        'description'       => fake()->paragraph(),
        'channel'           => core()->getCurrentChannel()->id,
        'locale'            => core()->getCurrentLocale()->id,
    ])
        ->assertRedirect(route('admin.catalog.products.index'))
        ->isRedirection();

    $this->assertDatabaseHas('products', [
        'sku'  => $sku,
        'type' => $product->type,
    ]);

    $this->assertDatabaseHas('product_flat', [
        'url_key' => $urlKey,
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
        ->assertJsonPath('file_name', $file->name);

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
