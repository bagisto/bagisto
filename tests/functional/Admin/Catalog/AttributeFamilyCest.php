<?php

namespace Tests\Functional\Admin\Catalog;

use FunctionalTester;
use Webkul\Attribute\Models\AttributeFamily;

class AttributeFamilyCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.families.index');
        $I->seeCurrentRouteIs('admin.catalog.families.index');

        $I->sendAjaxGetRequest(route('admin.catalog.families.index'));
        $I->seeResponseCodeIsSuccessful();
    }

    public function testCreate(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.families.index');

        $I->click(__('admin::app.catalog.families.add-family-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeCurrentRouteIs('admin.catalog.families.create');

        $I->click(__('admin::app.catalog.families.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testData = $this->fillForm($I);
        $I->click(__('admin::app.catalog.families.save-btn-title'), '//*[contains(@class, "page-action")]');

        $I->dontSeeFormErrors();
        $I->seeCurrentRouteIs('admin.catalog.families.index');
        $I->seeRecord(AttributeFamily::class, $testData);
    }

    public function testEdit(FunctionalTester $I): void
    {
        $attributeFamily = $I->have(AttributeFamily::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.families.index');

        $route = route('admin.catalog.families.edit', ['id' => $attributeFamily->id]);
        $I->amOnPage($route);
        $I->seeCurrentRouteIs('admin.catalog.families.edit');

        $I->fillField('name', '');
        $I->click(__('admin::app.catalog.families.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testData = $this->fillForm($I);
        $testData['id'] = $attributeFamily->id;
        $I->click(__('admin::app.catalog.families.save-btn-title'), '//*[contains(@class, "page-action")]');

        $I->dontSeeFormErrors();
        $I->seeRecord(AttributeFamily::class, $testData);
        $I->seeCurrentRouteIs('admin.catalog.families.index');
    }

    private function fillForm(FunctionalTester $I): array
    {
        $testData = [
            /**
             * Code needs to match: '/^[a-zA-Z]+[a-zA-Z0-9_]+$/'
             */
            'code' => $I->fake()->word . strtr($I->fake()->uuid, ['-' => '_']),
            'name' => $I->fake()->sentence,
        ];

        $I->fillField('code', $testData['code']);
        $I->fillField('name', $testData['name']);

        return $testData;
    }
}
