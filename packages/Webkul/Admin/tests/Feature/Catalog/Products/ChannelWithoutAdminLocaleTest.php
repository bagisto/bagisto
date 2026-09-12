<?php

use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\DataTransfer\Helpers\Import;
use Webkul\DataTransfer\Helpers\Importers\Product\Importer as ProductImporter;
use Webkul\DataTransfer\Models\Import as ImportModel;
use Webkul\DataTransfer\Models\ImportBatch;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    $this->channelLocale = Locale::factory()->create();

    $this->channel = Channel::query()->where('code', config('app.channel'))->first() ?? Channel::query()->first();

    $this->channel->locales()->sync([$this->channelLocale->id]);

    $this->channel->default_locale_id = $this->channelLocale->id;

    $this->channel->save();

    CacheGeneration::bump(ChannelRepository::class);

    core()->setDefaultChannel($this->channel);

    core()->setCurrentChannel($this->channel);
});

it('opens the product edit page in the channel locale when the channel lacks the admin locale', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->loginAsAdmin();

    $content = get(route('admin.catalog.products.edit', $product->id))
        ->assertOk()
        ->content();

    expect($content)->toMatch('/name="locale"\s+value="'.preg_quote($this->channelLocale->code, '/').'"/');
});

it('saves product content in the channel locale and lists the product in the admin grid when the channel lacks the admin locale', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.catalog.products.update', $product->id), [
        'sku' => $product->sku,
        'url_key' => fake()->slug(),
        'short_description' => fake()->sentence(),
        'description' => fake()->paragraph(),
        'name' => $name = fake()->words(3, true),
        'price' => fake()->randomFloat(2, 1, 1000),
        'weight' => fake()->numberBetween(0, 100),
        'channel' => $this->channel->code,
        'locale' => app()->getLocale(),
    ])
        ->assertRedirect(route('admin.catalog.products.index'));

    $this->assertModelWise([
        ProductAttributeValue::class => [
            [
                'product_id' => $product->id,
                'attribute_id' => Attribute::query()->where('code', 'name')->value('id'),
                'locale' => $this->channelLocale->code,
                'text_value' => $name,
            ],
        ],
    ]);

    $record = collect(
        getJson(route('admin.catalog.products.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ])
            ->assertOk()
            ->json('records')
    )->firstWhere('product_id', $product->id);

    expect($record)->not->toBeNull()
        ->and($record['name'])->toBe($name);
});

it('writes the admin locale flat row when indexing imported products on a channel that lacks it', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    ProductFlat::query()
        ->where('product_id', $product->id)
        ->where('locale', app()->getLocale())
        ->delete();

    $import = ImportModel::query()->create([
        'type' => 'products',
        'action' => Import::ACTION_APPEND,
        'validation_strategy' => Import::VALIDATION_STRATEGY_SKIP_ERRORS,
        'field_separator' => ',',
        'file_path' => 'imports/products.csv',
    ]);

    $batch = ImportBatch::query()->create([
        'import_id' => $import->id,
        'state' => Import::STATE_LINKED,
        'data' => [['sku' => $product->sku]],
    ]);

    app(ProductImporter::class)
        ->setImport($import)
        ->indexData($batch);

    $this->assertModelWise([
        ProductFlat::class => [
            [
                'product_id' => $product->id,
                'channel' => $this->channel->code,
                'locale' => app()->getLocale(),
                'sku' => $product->sku,
            ],
        ],
    ]);
});
