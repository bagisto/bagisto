<?php

namespace Tests\Functional\Admin\Customer;

use FunctionalTester;
use Webkul\Core\Models\SubscribersList;

class NewsletterSubscriptionCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $subscriber = $I->have(SubscribersList::class);

        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.customers.subscribers.index');
        $I->seeCurrentRouteIs('admin.customers.subscribers.index');

        $I->see("{$subscriber->id}", '//script[@type="text/x-template"]');
    }
}
