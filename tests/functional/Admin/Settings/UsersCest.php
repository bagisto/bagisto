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
         * Logged in user.
         */
        $admin = $I->loginAsAdmin();

        /**
         * Change the status.
         */
        $this->proceedToChangeStatus($I, $admin);

        /**
         * Current route should be user listing page.
         */
        $I->seeCurrentRouteIs('admin.users.index');

        /**
         * Grabbed latest record.
         */
        $latestRecord = $I->grabRecord(Admin::class, [
            'id' => $admin->id,
        ]);

        /**
         * Assertion.
         */
        $I->assertEquals(1, $latestRecord->status);
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
         * Logged in user.
         */
        $I->loginAsAdmin();

        /**
         * Created one more admin so that status get changed.
         */
        $anotherAdmin = $I->have(Admin::class);

        /**
         * Change the status.
         */
        $this->proceedToChangeStatus($I, $anotherAdmin);

        /**
         * Current route should be user listing page.
         */
        $I->seeCurrentRouteIs('admin.users.index');

        /**
         * Grabbed latest record.
         */
        $latestRecord = $I->grabRecord(Admin::class, [
            'id' => $anotherAdmin->id,
        ]);

        /**
         * Assertion.
         */
        $I->assertEquals(0, $latestRecord->status);
    }

    /**
     * Change the status of the admin.
     *
     * @param  FunctionalTester $I
     * @return void
     */
    private function proceedToChangeStatus(FunctionalTester $I, $editableAdmin = null): void
    {
        $I->amOnAdminRoute('admin.users.edit', ['id' => $editableAdmin->id], true);

        if (auth()->guard('admin')->user()->id !== $editableAdmin->id) {
            $I->seeElement('#status', ['value' => '1']);
            $I->uncheckOption('#status');
        } else {
            $I->dontSeeElement('#status');
        }

        $I->click(__('admin::app.users.users.save-btn-title'));
    }
}
