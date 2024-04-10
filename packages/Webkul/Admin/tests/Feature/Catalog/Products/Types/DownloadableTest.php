<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\Attribute;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should fail the validation with errors when certain inputs are not provided when store in downloadable product', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'))
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('attribute_family_id')
        ->assertJsonValidationErrorFor('sku')
        ->assertUnprocessable();
});

it('should return the create page of downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker())->getSimpleProductFactory()->create();

    $productId = $product->id + 1;

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.catalog.products.store'), [
        'type'                => 'downloadable',
        'attribute_family_id' => 1,
        'sku'                 => $sku = fake()->slug(),
    ])
        ->assertOk()
        ->assertJsonPath('data.redirect_url', route('admin.catalog.products.edit', $productId));

    $this->assertModelWise([
        Product::class => [
            [
                'id'   => $productId,
                'type' => 'downloadable',
                'sku'  => $sku,
            ],
        ],

        ProductFlat::class => [
            [
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
            ],
        ],
    ]);
});

it('should return the edit page of downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
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

it('should upload link the product upload link', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.catalog.products.upload_link', $product->id), [
        'file' => $file = UploadedFile::fake()->create(fake()->word().'.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->getClientOriginalName());

    if (Storage::disk('private')->exists($response['file'])) {
        Storage::disk('private')->delete($response['file']);
    }
});

it('should fail the validation with errors when certain inputs are not provided when update in downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id))
        ->assertJsonValidationErrorFor('sku')
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('short_description')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('price')
        ->assertUnprocessable();
});

it('should fail the validation with errors if certain data is not provided correctly in downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
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
        ->assertJsonValidationErrorFor('price')
        ->assertJsonValidationErrorFor('visible_individually')
        ->assertJsonValidationErrorFor('status')
        ->assertJsonValidationErrorFor('guest_checkout')
        ->assertJsonValidationErrorFor('new')
        ->assertJsonValidationErrorFor('featured')
        ->assertUnprocessable();
});

it('should upload the sample file', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.catalog.products.upload_sample', $product->id), [
        'file' => $file = UploadedFile::fake()->create(fake()->word().'.pdf', 100),
    ])
        ->assertOk()
        ->assertJsonPath('file_name', $file->name);

    if (Storage::disk('public')->exists($response['file'])) {
        Storage::disk('public')->delete($response['file']);
    }
});

it('should download the product which is downloadable', function () {
    // Arrange.
    $attribute = Attribute::factory()->create([
        'type' => 'file',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            $attribute->id => $attribute->code,
        ],

        'attribute_value' => [
            $attribute->code => [
                'text_value' => $file = UploadedFile::fake()->create(fake()->word().'.pdf', 100),
            ],
        ],
    ]))->getDownloadableProductFactory()->create();

    $fileName = $file->store('product/'.$product->id);

    $attributeValues = ProductAttributeValue::where('product_id', $product->id)
        ->where('attribute_id', $attribute->id)->first();

    $attributeValues->text_value = $fileName;
    $attributeValues->save();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.catalog.products.file.download', [$product->id, $attribute->id]))
        ->assertOk();

    Storage::assertExists($fileName);
});

it('should update the downloadable product', function () {
    // Arrange.
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

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), $data = [
        'sku'                => $product->sku,
        'url_key'            => $product->url_key,
        'short_description'  => fake()->sentence(),
        'description'        => fake()->paragraph(),
        'name'               => fake()->words(3, true),
        'price'              => fake()->randomFloat(2, 1, 1000),
        'weight'             => fake()->numberBetween(0, 100),
        'channel'            => core()->getCurrentChannelCode(),
        'locale'             => app()->getLocale(),
        'downloadable_links' => [
            'link_0' => [
                'en' => [
                    'title' => fake()->title,
                ],
                'price'       => rand(10, 250),
                'downloads'   => '1',
                'sort_order'  => '0',
                'type'        => 'file',
                'file'        => $file1 = UploadedFile::fake()->image('ProductImageExampleForUpload1.jpg'),
                'file_name'   => $file1->getClientOriginalName(),
                'sample_type' => 'url',
                'sample_url'  => fake()->url(),
            ],

            'link_1' => [
                'en' => [
                    'title' => fake()->title,
                ],
                'price'            => rand(10, 250),
                'downloads'        => '1',
                'sort_order'       => '1',
                'type'             => 'file',
                'file'             => $file2 = UploadedFile::fake()->image('ProductImageExampleForUpload2.jpg'),
                'file_name'        => $file2->getClientOriginalName(),
                'sample_type'      => 'file',
                'sample_file'      => $file3 = UploadedFile::fake()->image('ProductImageExampleForUpload3.jpg'),
                'sample_file_name' => $file3->getClientOriginalName(),
            ],
        ],

        'downloadable_samples' => [
            'sample_0' => [
                'title'      => fake()->title(),
                'sort_order' => '0',
                'type'       => 'file',
                'file'       => $file4 = UploadedFile::fake()->image('ProductImageExampleForUpload4.jpg'),
                'file_name'  => $file4->getClientOriginalName(),
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

    $this->assertModelWise([
        Product::class => [
            [
                'id'   => $product->id,
                'type' => $product->type,
                'sku'  => $product->sku,
            ],
        ],

        ProductFlat::class => [
            [
                'product_id'        => $product->id,
                'type'              => 'downloadable',
                'sku'               => $product->sku,
                'url_key'           => $product->url_key,
                'name'              => $data['name'],
                'short_description' => $data['short_description'],
                'description'       => $data['description'],
                'price'             => $data['price'],
                'weight'            => $data['weight'],
                'locale'            => $data['locale'],
                'channel'           => $data['channel'],
            ],
        ],
    ]);
});

it('should delete a downloadable product', function () {
    // Arrange.
    $product = (new ProductFaker())->getDownloadableProductFactory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.catalog.products.delete-success'));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);

    $this->assertDatabaseMissing('product_flat', ['product_id' => $product->id]);

    $this->assertDatabaseMissing('product_downloadable_links', ['product_id' => $product->id]);

    $this->assertDatabaseMissing('product_downloadable_samples', ['product_id' => $product->id]);
});
