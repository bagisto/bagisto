<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemAdapter;
use Webkul\Attribute\Models\Attribute;
use Webkul\Core\Models\Channel;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;
use Webkul\Product\Helpers\ConfigurableOption;
use Webkul\Shop\Tests\Fixtures\ImageCache\SquareProductCard;

beforeEach(function () {
    config(['imagecache.templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
    ]]);

    $this->attribute = Attribute::factory()->create([
        'type' => 'select',
        'swatch_type' => 'image',
    ]);

    $this->path = 'attribute_option/swatch-'.$this->attribute->id.'.png';

    Storage::put($this->path, UploadedFile::fake()->image('swatch.png', 300, 300)->getContent());

    $this->option = $this->attribute->options()->create([
        'admin_name' => 'Green',
        'sort_order' => 1,
        'swatch_value' => $this->path,
    ]);

    $this->product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->product->super_attributes()->attach($this->attribute->id);
});

afterEach(function () {
    Storage::delete($this->path);
});

/**
 * Register a storefront theme with image templates and a swatch image list, run by a channel on its own host.
 */
function channelRunningSwatchTemplates(string $code, ?array $templates, array $swatchImages = []): Channel
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), [
        'name' => ucfirst($code),
        'customize' => [
            'image_cache' => [
                'templates' => $templates,
                'swatch_images' => $swatchImages,
            ],
        ],
    ])]);

    return Channel::factory()->create([
        'theme' => $code,
        'hostname' => 'http://'.$code.'.test',
    ]);
}

/**
 * The swatch option the configurable product config hands the storefront on a channel.
 */
function swatchOptionOn(Channel $channel, $test): array
{
    core()->setCurrentChannel($channel);

    $attributes = app(ConfigurableOption::class)->getAttributesData($test->product, [
        $test->attribute->id => [$test->option->id => [$test->product->id]],
    ]);

    return collect($attributes)->firstWhere('id', $test->attribute->id)['options'][0];
}

it('should give the swatch value url of the model as the url of the stored file', function () {
    expect($this->option->fresh()->swatch_value_url)->toBe(Storage::url($this->path));
});

it('should keep a theme template out of the swatch value url of the model', function () {
    core()->setCurrentChannel(channelRunningSwatchTemplates('fashion', ['small' => SquareProductCard::class], ['small']));

    expect($this->option->fresh()->swatch_value_url)->toBe(Storage::url($this->path))
        ->and($this->option->fresh()->swatch_value_url)->not->toContain('cache/');
});

it('should give an image swatch the core sizes and the original when the theme lists nothing', function () {
    $option = swatchOptionOn(channelRunningSwatchTemplates('plain', null), $this);

    expect($option['swatch_value'])->toBe(url('cache/small/'.$this->path))
        ->and($option['swatch_image'])->toBe([
            'small_image_url' => url('cache/small/'.$this->path),
            'medium_image_url' => url('cache/medium/'.$this->path),
            'large_image_url' => url('cache/large/'.$this->path),
            'original_image_url' => url('cache/original/'.$this->path),
            'alt' => 'Green',
        ]);
});

it('should give an image swatch a url for a template the theme lists for swatch images', function () {
    $swatch = channelRunningSwatchTemplates('fashion', ['swatch_card' => SquareProductCard::class], ['swatch_card']);

    $plain = channelRunningSwatchTemplates('plain', ['swatch_card' => SquareProductCard::class]);

    expect(swatchOptionOn($swatch, $this)['swatch_image']['swatch_card_image_url'])->toBe(url('cache/swatch_card/'.$this->path))
        ->and(swatchOptionOn($plain, $this)['swatch_image'])->not->toHaveKey('swatch_card_image_url');
});

it('should give an image swatch as its stored file when the attributes are asked for without image urls', function () {
    core()->setCurrentChannel(channelRunningSwatchTemplates('fashion', ['swatch_card' => SquareProductCard::class], ['swatch_card']));

    $attributes = app(ConfigurableOption::class)->getAttributesData($this->product, [
        $this->attribute->id => [$this->option->id => [$this->product->id]],
    ], false);

    $option = collect($attributes)->firstWhere('id', $this->attribute->id)['options'][0];

    expect($option['swatch_value'])->toBe(Storage::url($this->path))
        ->and($option)->not->toHaveKey('swatch_image');
});

it('should give a color swatch no swatch image', function () {
    $this->attribute->update(['swatch_type' => 'color']);

    $this->option->update(['swatch_value' => '#33aa66']);

    $option = swatchOptionOn(core()->getDefaultChannel(), $this);

    expect($option['swatch_image'])->toBeNull()
        ->and($option['swatch_value'])->toBe('#33aa66');
});

it('should size an image swatch through the image cache on a disk that is not local', function () {
    Storage::shouldReceive('getAdapter')->andReturn(Mockery::mock(FilesystemAdapter::class));

    Storage::shouldReceive('url')->andReturnUsing(fn ($path) => 'https://cdn.example.com/'.$path);

    Storage::shouldReceive('delete');

    $option = swatchOptionOn(core()->getDefaultChannel(), $this);

    expect($option['swatch_value'])->toBe(url('cache/small/'.$this->path))
        ->and($option['swatch_image']['large_image_url'])->toBe(url('cache/large/'.$this->path));
});
