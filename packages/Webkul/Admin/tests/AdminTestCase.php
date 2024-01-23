<?php

namespace Webkul\Admin\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminSweeper;
use Webkul\User\Contracts\Admin as AdminContract;
use Webkul\User\Models\Admin as AdminModel;

class AdminTestCase extends TestCase
{
    use AdminSweeper;

    /**
     * Login as customer.
     */
    public function loginAsAdmin(?AdminContract $admin = null): AdminContract
    {
        $admin = $admin ?? AdminModel::factory()->create();

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanAll();
    }
}
