<?php

use Webkul\Core\Enums\CurrencyPositionEnum;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;

it('returns all channels', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    $channels = core()->getAllChannels();

    // Assert
    expect($channels->count())->toBe(2);
    expect($channels->where('id', $expectedChannel->id))->toBeTruthy();
});

it('returns the current channel', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    $channel = core()->getCurrentChannel($expectedChannel->hostname);

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the current channel when set via setter', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    core()->setCurrentChannel($expectedChannel);

    $channel = core()->getCurrentChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the current channel code', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    core()->setCurrentChannel($expectedChannel);

    $channelCode = core()->getCurrentChannelCode();

    // Assert
    expect($channelCode)->toBe($expectedChannel->code);
});

it('returns the default channel', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    config()->set('app.channel', $expectedChannel->code);

    // Act
    $channel = core()->getDefaultChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the first channel if the default channel is not found', function () {
    // Arrange
    $expectedChannel = Channel::first();

    config()->set('app.channel', 'wrong_channel_code');

    // Act
    $channel = core()->getDefaultChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the default channel when set via setter', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    core()->setDefaultChannel($expectedChannel);

    $channel = core()->getDefaultChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the default channel code', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    core()->setDefaultChannel($expectedChannel);

    $channelCode = core()->getDefaultChannelCode();

    // Assert
    expect($channelCode)->toBe($expectedChannel->code);
});

it('returns the requested channel', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    request()->merge([
        'channel' => $expectedChannel->code,
    ]);

    // Act
    $channel = core()->getRequestedChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the current channel if the requested channel code is not provided', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    core()->setCurrentChannel($expectedChannel);

    // Act
    $channel = core()->getRequestedChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->code)->toBe($expectedChannel->code);
});

it('returns the requested channel code', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    request()->merge([
        'channel' => $expectedChannel->code,
    ]);

    // Act
    $channelCode = core()->getRequestedChannelCode();

    // Assert
    expect($channelCode)->toBe($expectedChannel->code);
});

it('returns the current channel code if requested channel code is not provided', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    core()->setCurrentChannel($expectedChannel);

    // Act
    $channelCode = core()->getRequestedChannelCode();

    // Assert
    expect($channelCode)->toBe($expectedChannel->code);
});

it('should format the price with default symbol based on the current currency and use default formatter if currency position is not defined', function () {
    // Arrange
    $expectedSymbol = '$';

    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => null,
        'currency_position' => null,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($expectedSymbol.$price);
});

it('should format the price with custom symbol based on the current currency and use default formatter if currency position is not defined', function () {
    // Arrange
    $expectedSymbol = '€';

    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => $expectedSymbol,
        'currency_position' => null,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($expectedSymbol.$price);
});

it('should format the price based on the current currency and place the symbol on the left side', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($channel->base_currency->symbol.$price);
});

it('should format the price based on the current currency and place the symbol on the left side with space', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($channel->base_currency->symbol.' '.$price);
});

it('should format the price based on the current currency and place the symbol on the right side', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.$channel->base_currency->symbol);
});

it('should format the price based on the current currency and place the symbol on the right side with space', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$channel->base_currency->symbol);
});

it('should format the price based on the current currency and place the code on the left side if the symbol is not present', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($channel->base_currency->code.$price);
});

it('should format the price based on the current currency and place the code on the left side with space if the symbol is not present', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($channel->base_currency->code.' '.$price);
});

it('should format the price based on the current currency and place the code on the right side if the symbol is not present', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.$channel->base_currency->code);
});

it('should format the price based on the current currency and place the code on the right side with space if the symbol is not present', function () {
    // Arrange
    $channel = Channel::factory()->create();

    $channel->base_currency->update([
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $channel->base_currency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$channel->base_currency->code);
});

it('should format the price based on the mentioned currency and place the symbol on the left side', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->symbol.$price);
});

it('should format the price based on the mentioned currency and place the symbol on the left side with space', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->symbol.' '.$price);
});

it('should format the price based on the mentioned currency and place the symbol on the right side', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($price.$indianCurrency->symbol);
});

it('should format the price based on the mentioned currency and place the symbol on the right side with space', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$indianCurrency->symbol);
});

it('should format the price based on the mentioned currency and place the code on the left side if the symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->code.$price);
});

it('should format the price based on the mentioned currency and place the code on the left side with space if the symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->code.' '.$price);
});

it('should format the price based on the mentioned currency and place the code on the right side if the symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($price.$indianCurrency->code);
});

it('should format the price based on the mentioned currency and place the code on the right side with space if the symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    $channel = Channel::factory()->create();

    $channel->currencies()->sync(Currency::all()->pluck('id')->toArray());

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    core()->setCurrentChannel($channel);

    // Act
    $formattedPrice = core()->formatPrice($price, $indianCurrency->code);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$indianCurrency->code);
});

it('should format the base price and place the symbol on the left side', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->symbol.$price);
});

it('should format the base price and place the symbol on the left side with space', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->symbol.' '.$price);
});

it('should format the base price and place the symbol on the right side', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.$indianCurrency->symbol);
});

it('should format the base price and place the symbol on the right side with space', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '₹',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$indianCurrency->symbol);
});

it('should format the base price and place the code on the left side if symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->code.$price);
});

it('should format the base price and place the code on the left side with space if symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::LEFT_WITH_SPACE->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($indianCurrency->code.' '.$price);
});

it('should format the base price and place the code on the right side if symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.$indianCurrency->code);
});

it('should format the base price and place the code on the right side with space if symbol is not present', function () {
    // Arrange
    $indianCurrency = Currency::factory()->create([
        'code'              => 'INR',
        'name'              => 'Indian Rupee',
        'symbol'            => '',
        'currency_position' => CurrencyPositionEnum::RIGHT_WITH_SPACE->value,
    ]);

    config()->set('app.currency', $indianCurrency->code);

    $price = number_format(fake()->randomFloat(min: 1, max: 500), $indianCurrency->decimal);

    // Act
    $formattedPrice = core()->formatBasePrice($price);

    // Assert
    expect($formattedPrice)->toBe($price.' '.$indianCurrency->code);
});
