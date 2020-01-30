<?php

namespace Tests\Functional\Admin\Catalog;


use FunctionalTester;
use Webkul\Attribute\Models\Attribute;

class AttributeCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $attribute = $I->have(Attribute::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.catalog'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.attributes'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.catalog.attributes.index');
        $I->see($attribute->id, '//script[@type="text/x-template"]');
        $I->see($attribute->admin_name, '//script[@type="text/x-template"]');
    }

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

    public function testEdit(FunctionalTester $I): void
    {
        $attribute = $I->have(Attribute::class, ['use_in_flat' => 0]);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.attributes.index');

        $route = route('admin.catalog.attributes.edit', ['id' => $attribute->id]);
        $I->seeInSource($route);
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
     * @param FunctionalTester $I
     *
     * @param bool             $skipType
     *
     * @return array with the test-data
     */
    private function fillForm(FunctionalTester $I, bool $skipType = false): array
    {
        $testData = [
            'code'       => $I->fake()->word,
            'type'       => $I->fake()->randomElement([
                'text',
                'textarea',
                'price',
                'boolean',
                'select',
                'multiselect'
            ]),
            'admin_name' => $I->fake()->word,
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
