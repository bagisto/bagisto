<?php

use Webkul\PayGlocal\Payment\PayGlocal;

return [
    'payglocal' => [
        'code' => 'payglocal',
        'title' => 'PayGlocal',
        'description' => 'PayGlocal',
        'class' => PayGlocal::class,
        'active' => false,
        'accepted_currencies' => 'USD',
        'sandbox' => true,
        'sort' => 9,
    ],
];
