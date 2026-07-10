import { test } from "../../../setup";
import { TaxRateCreatePage } from "../../../pages/admin/settings/taxes/TaxRateCreatePage";
import { TaxRateEditPage } from "../../../pages/admin/settings/taxes/TaxRateEditPage";
import { TaxRateListPage } from "../../../pages/admin/settings/taxes/TaxRateListPage";
import { TAX_REGIONS } from "../../../utils/tax";

/**
 * Tax Rate management — full CRUD, validations and grid operations driven
 * entirely through the Page Object Model.
 */
test.describe("tax rates", () => {
    test.describe("creation", () => {
        test("should create a tax rate with all valid fields and list it in the grid", async ({
            adminPage,
        }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate({
                country: TAX_REGIONS.india.country,
                state: TAX_REGIONS.india.checkoutState,
                taxRate: "18",
            });

            await listPage.open();
            await listPage.search(rate.identifier);
            await listPage.expectRowVisible(rate.identifier);
        });

        test("should create a tax rate restricted by a zip range", async ({
            adminPage,
        }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate({
                taxRate: "5",
                isZip: true,
                zipFrom: "100000",
                zipTo: "199999",
            });

            await listPage.open();
            await listPage.search(rate.identifier);
            await listPage.expectRowVisible(rate.identifier);
        });
    });

    test.describe("validation", () => {
        test("should reject creation when required fields are empty", async ({
            adminPage,
        }) => {
            await new TaxRateCreatePage(adminPage).expectRequiredFieldErrors();
        });

        test("should reject an out-of-range tax percentage", async ({
            adminPage,
        }) => {
            await new TaxRateCreatePage(adminPage).expectInvalidPercentageError(
                "150",
            );
        });

        test("should reject a duplicate identifier", async ({ adminPage }) => {
            const createPage = new TaxRateCreatePage(adminPage);

            const rate = await createPage.createTaxRate();

            await createPage.expectDuplicateIdentifierError(rate.identifier);
        });
    });

    test.describe("update", () => {
        test("should edit a tax rate and reflect the new value in the form and grid", async ({
            adminPage,
        }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const editPage = new TaxRateEditPage(adminPage);

            const rate = await createPage.createTaxRate({ taxRate: "12" });

            await editPage.updateTaxRate(rate.identifier, { taxRate: "22" });
            await editPage.expectGridValue(rate.identifier, "22");
            await editPage.expectFormValue(rate.identifier, "22");
        });
    });

    test.describe("delete", () => {
        test("should delete a tax rate and remove it from the grid", async ({
            adminPage,
        }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate();

            await listPage.deleteTaxRate(rate.identifier);
        });
    });

    test.describe("grid operations", () => {
        test("should search a tax rate by identifier", async ({ adminPage }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate();

            await listPage.open();
            await listPage.search(rate.identifier);
            await listPage.expectRowVisible(rate.identifier);
        });

        test("should sort the grid by the identifier column", async ({
            adminPage,
        }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate();

            await listPage.open();
            await listPage.sortByColumn("Identifier");
            await listPage.search(rate.identifier);
            await listPage.expectRowVisible(rate.identifier);
        });

        test("should filter the grid by identifier", async ({ adminPage }) => {
            const createPage = new TaxRateCreatePage(adminPage);
            const listPage = new TaxRateListPage(adminPage);

            const rate = await createPage.createTaxRate();

            await listPage.open();
            await listPage.filterByColumn("Identifier", rate.identifier);
            await listPage.expectRowVisible(rate.identifier);
        });

        /**
         * The Tax Rate datagrid declares no mass actions, so mass-action
         * coverage is intentionally not implemented (scoped to "if available").
         */
    });
});
