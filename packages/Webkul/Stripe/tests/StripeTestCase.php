<?php

namespace Webkul\Stripe\Tests;

use Tests\TestCase;
use Webkul\Payment\Tests\Concerns\ProvidePaymentHelpers;
use Webkul\Stripe\Tests\Concerns\FakesStripeApi;

class StripeTestCase extends TestCase
{
    use FakesStripeApi, ProvidePaymentHelpers;
}
