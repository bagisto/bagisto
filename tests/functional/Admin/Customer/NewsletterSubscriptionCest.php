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
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.marketing'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.email-marketing'), '//*[contains(@class, "aside-nav")]');
        $I->click(__('admin::app.layouts.newsletter-subscriptions'), '//*[contains(@class, "tabs")]');

        $I->seeCurrentRouteIs('admin.customers.subscribers.index');
        $I->see($subscriber->id, '//script[@type="text/x-template"]');
        $I->see($subscriber->email, '//script[@type="text/x-template"]');
    }
}
