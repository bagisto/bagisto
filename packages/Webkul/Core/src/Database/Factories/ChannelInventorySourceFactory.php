<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define('channel_inventory_sources', function () {
    return [
        'channel_id'          => core()->getCurrentChannel()->id,
        'inventory_source_id' => 1,
    ];
});