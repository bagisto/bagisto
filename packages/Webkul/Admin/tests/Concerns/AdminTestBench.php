<?php

namespace Webkul\Admin\Tests\Concerns;

use Webkul\User\Contracts\Admin as AdminContract;
use Webkul\User\Models\Admin as AdminModel;
use Webkul\User\Models\Role;

trait AdminTestBench
{
    /**
     * Login as admin.
     */
    public function loginAsAdmin(?AdminContract $admin = null): AdminContract
    {
        $admin = $admin ?? AdminModel::factory()->create();

        $this->actingAs($admin, 'admin');

        return $admin;
    }

    /**
     * Create an admin with a custom role limited to the given permissions and login.
     */
    public function loginAsAdminWithPermissions(array $permissions): AdminContract
    {
        $role = Role::factory()->create([
            'permission_type' => 'custom',
            'permissions' => $permissions,
        ]);

        $admin = AdminModel::factory()->create([
            'role_id' => $role->id,
        ]);

        return $this->loginAsAdmin($admin);
    }
}
