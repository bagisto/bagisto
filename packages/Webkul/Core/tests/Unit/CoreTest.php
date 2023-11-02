<?php

use Webkul\Core\Models\Channel;

afterEach(function () {
    /**
     * Clean channels, excluding ID 1 (i.e., the default channel). A fresh instance will always have ID 1.
     */
    Channel::query()->whereNot('id', 1)->delete();
});

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
