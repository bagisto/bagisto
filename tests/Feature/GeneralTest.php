<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Webkul\Category\Repositories\CategoryRepository;

class GeneralTest extends TestCase
{
    /**
     * Test for home page
     *
     * @return void
     */
    public function testHomePage()
    {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test for customer login
     *
     * @return void
     */
    public function testCustomerLoginPage()
    {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get('/customer/login');

        $response->assertStatus(200);
    }

    /**
     * Test for categories page
     *
     * @return void
     */
    public function testCategoriesPage()
    {
        $categoryUrlSlug = 'marvel-figurines';

        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get("/categories/{$categoryUrlSlug}");

        $response->assertStatus(200);
    }

    /**
     * Test for customer registration page
     *
     * @return void
     */
    public function testCustomerRegistrationPage()
    {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get("/customer/register");

        $response->assertStatus(200);
    }

    /**
     * Test for checkout's cart page
     *
     * @return void
     */
    public function testCartPage()
    {
        config(['app.url' => 'http://127.0.0.1:8000']);

        $response = $this->get("/checkout/cart");

        $response->assertStatus(200);
    }
}