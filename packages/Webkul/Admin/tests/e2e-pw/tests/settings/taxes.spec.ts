import { test, expect } from "../../setup";
import { TaxesPage } from "../../pages/admin/settings/TaxesPage";

test.describe("tax management", () => {
    test.describe("tax rate management", () => {
        test("should create a tax rate without zip range", async ({
            adminPage,
        }) => {
            const taxesPage = new TaxesPage(adminPage);
            const taxRate = await taxesPage.createTaxRate();

            await expect(
                adminPage.getByText("Tax rate created successfully.").first(),
            ).toBeVisible();

            await expect(adminPage.getByText(taxRate.identifier)).toBeVisible();
        });

        test("should edit a tax rate without zip range", async ({
            adminPage,
        }) => {
            const taxesPage = new TaxesPage(adminPage);
            await taxesPage.createTaxRate();
            await taxesPage.editFirstTaxRate();
        });

        test("should delete a tax rate", async ({ adminPage }) => {
            const taxesPage = new TaxesPage(adminPage);
            await taxesPage.createTaxRate();
            await taxesPage.deleteFirstTaxRate();
        });
    });

    test.describe("tax category management", () => {
        test("should create a tax category", async ({ adminPage }) => {
            const taxesPage = new TaxesPage(adminPage);
            await taxesPage.createTaxCategory();
        });

        test("should edit a tax category", async ({ adminPage }) => {
            const taxesPage = new TaxesPage(adminPage);
            await taxesPage.editFirstTaxCategory();
        });
    });
});
