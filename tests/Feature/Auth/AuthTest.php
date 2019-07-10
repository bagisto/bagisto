<?php

namespace Tests\Feature;

use Tests\TestCase;

use Auth;
use Crypt;

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
            'first_name' => explode(' ', $faker->name)[0],
            'last_name' => explode(' ', $faker->name)[0],
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
        config(['app.url' => 'http://127.0.0.1:8000']);

        $customers = app(Customer::class);
        $customer = $customers->findOneByField('email', 'john@doe.net');

        $response = $this->post('/customer/login', [
            'email' => $customer->email,
            'password' => '12345678'
        ]);

        $response->assertRedirect('/customer/account/profile');
    }

        /**
         * Test that customer cannot login with the wrong credentials.
         */
        public function willNotLoginWithWrongCredentials()
        {
            $customers = app(Customer::class);
            $customer = $customers->findOneByField('email', 'john@doe.net');

            $response = $this->from(route('login'))->post(route('customer.session.create'),
                        [
                            'email' => $customer->email,
                            'password' => 'wrongpassword3428903mlndvsnljkvsd',
                        ]);

            $this->assertGuest();
        }

        /**
         * Test to confirm that customer cannot login if user does not exist.
         */
        public function willNotLoginWithNonexistingCustomer()
        {
            $response = $this->post(route('customer.session.create'), [
                            'email' => 'fiaiia9q2943jklq34h203qtb3o2@something.com',
                            'password' => 'wrong-password',
                        ]);

            $this->assertGuest();
        }

        /**
         * To test that customer can logout
         */
        public function allowsCustomerToLogout()
        {
            $customer = auth()->guard('customer')->user();

            // dd('logout test', $customer);

            $this->get(route('customer.session.destroy'));

            $this->assertGuest();
        }
}
