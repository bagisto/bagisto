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
    expect($channel->hostname)->toBe($expectedChannel->hostname);
});

it('returns the current channel when set via setter', function () {
    // Arrange
    $expectedChannel = Channel::factory()->create();

    // Act
    core()->setCurrentChannel($expectedChannel);

    $channel = core()->getCurrentChannel();

    // Assert
    expect($channel->id)->toBe($expectedChannel->id);
    expect($channel->hostname)->toBe($expectedChannel->hostname);
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
