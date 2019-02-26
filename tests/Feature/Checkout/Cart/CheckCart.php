<?php

namespace Tests\Feature;

use Tests\TestCase;

use App;

use Cart;

use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository as Customer;

class AuthTest extends TestCase
{
    /**
     * Add a simple Item in cart for guest
     */
    public function addSimpleItemGuest() {

    }

    /**
     * Add a configurable Item in cart for guest
     */
    public function addConfigurableItemGuest() {
    }

     /**
     * Remove an Item in cart for guest
     */
    public function removeItemGuest() {

    }
}
