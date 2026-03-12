<?php

use Webkul\PhonePe\Payment\PhonePe;

return [
    'phonepe' => [
        'code' => 'phonepe',
        'title' => 'PhonePe',
        'description' => 'PhonePe',
        'class' => PhonePe::class,
        'active' => true,
        "sandbox" => true,
        'sort' => 4,
    ],
];