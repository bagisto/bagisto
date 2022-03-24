<?php

namespace Tests\Functional\Admin\Catalog;

use FunctionalTester;
use Webkul\Attribute\Models\Attribute;

class AttributeCest
{
    /**
     * Test attribute index page.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.attributes.index');
        $I->seeCurrentRouteIs('admin.catalog.attributes.index');

        $I->sendAjaxGetRequest(route('admin.catalog.attributes.index'));
        $I->seeResponseCodeIsSuccessful();
    }

    /**
     * Attribute creation test.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testCreate(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.attributes.index');

        $I->click('Add Attribute', '//*[contains(@class, "page-action")]');
        $I->seeCurrentRouteIs('admin.catalog.attributes.create');

        $I->click('Save Attribute', '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testData = $this->fillForm($I);
        $I->click('Save Attribute', '//*[contains(@class, "page-action")]');

        $I->dontSeeFormErrors();
        $I->seeCurrentRouteIs('admin.catalog.attributes.index');
        $I->seeRecord(Attribute::class, $testData);
    }

    /**
     * Attribute updation test.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testEdit(FunctionalTester $I): void
    {
        $attribute = $I->have(Attribute::class, ['use_in_flat' => 0]);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.attributes.index');

        $route = route('admin.catalog.attributes.edit', ['id' => $attribute->id]);
        $I->amOnPage($route);
        $I->seeCurrentRouteIs('admin.catalog.attributes.edit');

        $I->fillField('admin_name', '');
        $I->click('Save Attribute', '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testData = $this->fillForm($I, true);
        $testData['id'] = $attribute->id;
        $I->click('Save Attribute', '//*[contains(@class, "page-action")]');

        $I->dontSeeFormErrors();
        $I->seeRecord(Attribute::class, $testData);
        $I->seeCurrentRouteIs('admin.catalog.attributes.index');
    }

    /**
     * Fill form.
     *
     * @param  FunctionalTester  $I
     * @param  bool  $skipType
     * @return array
     */
    private function fillForm(FunctionalTester $I, bool $skipType = false): array
    {
        $testData = [
            'code'       => $I->fake()->firstName,
            'type'       => $I->fake()->randomElement([
                'text',
                'textarea',
                'price',
                'boolean',
                'select',
                'multiselect',
            ]),
            'admin_name' => $I->fake()->firstName,
        ];

        $I->fillField('code', $testData['code']);
        $I->fillField('admin_name', $testData['admin_name']);

        if ($skipType) {
            unset($testData['type']);
        } else {
            $I->selectOption('type', $testData['type']);
        }

        return $testData;
    }
}
