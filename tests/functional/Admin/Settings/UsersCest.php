<?php

namespace Tests\Functional\Admin\Settings;

use FunctionalTester;
use Webkul\User\Models\Admin;

class UsersCest
{
    /**
     * Index page test.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->seeCurrentRouteIs('admin.dashboard.index');
    }

    /**
     * Test inactive status when there is single admin present.
     *
     * @param  FunctionalTester $I
     * @return void
     */
    public function testAdminInactiveStatusWithSingleAdmin(FunctionalTester $I): void
    {
        /**
         * Change the status.
         */
        $this->proceedToChangeStatus($I);

        /**
         * Current route should be user listing page with error.
         */
        $I->seeCurrentRouteIs('admin.users.index');
    }

    /**
     * Test inactive status when there are more admins present.
     *
     * @param  FunctionalTester $I
     * @return void
     */
    public function testAdminInactiveStatusWhenMoreAdminsPresent(FunctionalTester $I): void
    {
        /**
         * Created one more admin so that status get changed.
         */
        $I->have(Admin::class);

        /**
         * Change the status.
         */
        $this->proceedToChangeStatus($I);

        /**
         * Admin should be logged out.
         */
        $I->seeCurrentRouteIs('admin.session.create');
    }

    /**
     * Change the status of the admin.
     *
     * @param  FunctionalTester $I
     * @return void
     */
    private function proceedToChangeStatus(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.users.edit', ['id' => 1], true);

        $I->seeElement('#status', ['value' => '1']);
        $I->uncheckOption('#status');

        $I->click(__('admin::app.users.users.save-btn-title'));
    }
}
