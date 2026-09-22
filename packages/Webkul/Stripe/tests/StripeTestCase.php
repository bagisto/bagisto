<?php

namespace Webkul\Stripe\Tests;

use Stripe\ApiRequestor;
use Tests\TestCase;
use Webkul\Payment\Tests\Concerns\ProvidePaymentHelpers;
use Webkul\Stripe\Tests\Fixtures\FakeStripeHttpClient;

class StripeTestCase extends TestCase
{
    use ProvidePaymentHelpers;

    /**
     * Answer every call to the Stripe API from the given responses for the rest of the test.
     */
    public function fakeStripeApi(array $responses = []): FakeStripeHttpClient
    {
        ApiRequestor::setHttpClient($client = new FakeStripeHttpClient($responses));

        return $client;
    }

    /**
     * Hand the Stripe API back to its real client once the test is done.
     */
    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(null);

        parent::tearDown();
    }
}
