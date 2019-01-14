<?php

namespace Tests\Feature;

use Tests\TestCase;

use Auth;

use App;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Customer\Repositories\CustomerRepository as Customer;

class AuthTest extends TestCase
{
    protected $customer;

    /**
     * To check if the customer can view the login page or not
     *
     * @return void
     */
    public function testCustomerLoginPage()
    {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get('/customer/login');

        $response->assertSuccessful();

        $response->assertViewIs('shop::customers.session.index');
    }

    public function testCustomerResgistrationPage() {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get('/customer/register');

        $response->assertSuccessful();

        $response->assertViewIs('shop::customers.signup.index');
    }

    public function testCustomerRegistration() {
        $faker = \Faker\Factory::create();

        $allCustomers = array();

        $customers = app(Customer::class);

        $created = $customers->create([
            'first_name' => explode(' ',$faker->name)[0],
            'last_name' => explode(' ',$faker->name)[0],
            'channel_id' => core()->getCurrentChannel()->id,
            'gender' => $faker->randomElement($array = array ('Male','Female', 'Other')),
            'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'email' => $faker->email,
            'password' => bcrypt('12345678'),
            'is_verified' => 1
        ]);

        $this->assertEquals($created->id, $created->id);
    }

    public function testCustomerLogin() {
        $customers = app(Customer::class);

        $customer = $customers->find(1);

        $user = ['email' => $customer->email, 'password' => $customer->password];

        $this->assertAuthenticatedAs($user);
    }
}
