import { test } from "../../../setup";
import { TaxCategoriesPage } from "../../../pages/admin/settings/taxes/TaxCategoriesPage";
import { TaxRatesPage } from "../../../pages/admin/settings/taxes/TaxRatesPage";
import {
    generateTaxCategoryData,
    generateTaxRateData,
    type TaxRateData,
} from "../../../utils/tax";

test.describe("tax category management", () => {
    let taxRatesPage: TaxRatesPage;
    let taxCategoriesPage: TaxCategoriesPage;
    let rate: TaxRateData;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        taxRatesPage = new TaxRatesPage(adminPage);
        taxCategoriesPage = new TaxCategoriesPage(adminPage);
        rate = generateTaxRateData();
        created = [];

        await taxRatesPage.createTaxRate(rate);
    });

    test.afterEach(async () => {
        try {
            await taxCategoriesPage.deleteTaxCategoriesIfPresent(created);
        } finally {
            await taxRatesPage.deleteTaxRatesIfPresent([rate.identifier]);
        }
    });

    test("should create a tax category with a rate and keep the rate assigned", async () => {
        const category = generateTaxCategoryData();
        created.push(category.name);

        await taxCategoriesPage.createTaxCategory(category, [rate.identifier]);

        await taxCategoriesPage.expectTaxCategoryListed(category);
        await taxCategoriesPage.expectRatesAssignedInEditForm(category.name, [
            rate.identifier,
        ]);
    });

    test("should reject a tax category without its required fields", async () => {
        await taxCategoriesPage.submitEmptyCreateForm();

        await taxCategoriesPage.expectValidationError("The Code field is required");
        await taxCategoriesPage.expectValidationError("The Name field is required");
        await taxCategoriesPage.expectValidationError(
            "The Description field is required",
        );
        await taxCategoriesPage.expectValidationError(
            "The Tax Rates field is required",
        );
    });

    test("should rename a tax category and keep the new name after reload", async () => {
        const category = generateTaxCategoryData();
        const newName = generateTaxCategoryData().name;
        created.push(category.name, newName);

        await taxCategoriesPage.createTaxCategory(category, [rate.identifier]);
        await taxCategoriesPage.updateTaxCategory(category.name, { name: newName });

        await taxCategoriesPage.expectTaxCategoryListed({ ...category, name: newName });
        await taxCategoriesPage.expectTaxCategoryAbsent(category.name);
    });

    test("should delete a tax category and remove it from the grid", async () => {
        const category = generateTaxCategoryData();
        const untouched = generateTaxCategoryData();
        created.push(category.name, untouched.name);

        await taxCategoriesPage.createTaxCategory(category, [rate.identifier]);
        await taxCategoriesPage.createTaxCategory(untouched, [rate.identifier]);
        await taxCategoriesPage.deleteTaxCategory(category.name);

        await taxCategoriesPage.expectTaxCategoryAbsent(category.name);
        await taxCategoriesPage.expectTaxCategoryListed(untouched);
    });
});
