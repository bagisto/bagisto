<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $customer = app('Webkul\Customer\Repositories\CustomerRepository');

        $customer = $customer->all();

        $customer = $customer->first();

        $this->browse(function (Browser $browser) use($customer) {
            $browser->visit('/customer/login')
                ->type('email', $customer->email)
                ->type('password', $customer->password)
                ->click('input[type="submit"]')
                ->screenshot('error');
        });
    }
}
