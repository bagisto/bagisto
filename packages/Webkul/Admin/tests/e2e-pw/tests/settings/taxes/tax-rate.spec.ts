import { test } from "../../../setup";
import { TaxRatesPage } from "../../../pages/admin/settings/taxes/TaxRatesPage";
import { generateTaxRateData, TAX_REGIONS } from "../../../utils/tax";

test.describe("tax rate management", () => {
    let taxRatesPage: TaxRatesPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        taxRatesPage = new TaxRatesPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await taxRatesPage.deleteTaxRatesIfPresent(created);
    });

    test("should create a tax rate for a state and list it with its country and rate", async () => {
        const rate = generateTaxRateData({
            country: TAX_REGIONS.india.country,
            state: TAX_REGIONS.india.checkoutState,
            taxRate: "18",
        });
        created.push(rate.identifier);

        await taxRatesPage.createTaxRate(rate);

        await taxRatesPage.expectTaxRateListed(rate);
    });

    test("should create a tax rate restricted to a zip range", async () => {
        const rate = generateTaxRateData({
            taxRate: "5",
            isZip: true,
            zipFrom: "100000",
            zipTo: "199999",
        });
        created.push(rate.identifier);

        await taxRatesPage.createTaxRate(rate);

        await taxRatesPage.expectTaxRateListed(rate);
    });

    test("should reject a tax rate without an identifier and country", async () => {
        await taxRatesPage.submitEmptyCreateForm();

        await taxRatesPage.expectValidationError("The Identifier field is required");
        await taxRatesPage.expectValidationError("The Country field is required");
        await taxRatesPage.expectStillOnCreateForm();
    });

    test("should reject a tax rate above one hundred percent", async () => {
        const rate = generateTaxRateData({ taxRate: "150" });
        created.push(rate.identifier);

        await taxRatesPage.attemptCreateTaxRate(rate);

        await taxRatesPage.expectValidationError("must not be greater than 100");
        await taxRatesPage.expectTaxRateAbsent(rate.identifier);
    });

    test("should reject a tax rate whose identifier is already used", async () => {
        const existing = generateTaxRateData();
        const duplicate = generateTaxRateData({
            identifier: existing.identifier,
            taxRate: "7",
        });
        created.push(existing.identifier);

        await taxRatesPage.createTaxRate(existing);
        await taxRatesPage.attemptCreateTaxRate(duplicate);

        await taxRatesPage.expectValidationError(
            "The identifier has already been taken.",
        );
        await taxRatesPage.expectTaxRateListed(existing);
    });

    test("should update the rate and keep the new value after reload", async () => {
        const rate = generateTaxRateData({ taxRate: "12" });
        created.push(rate.identifier);

        await taxRatesPage.createTaxRate(rate);
        await taxRatesPage.updateTaxRate(rate.identifier, { taxRate: "22" });

        await taxRatesPage.expectTaxRateListed({ ...rate, taxRate: "22" });
        await taxRatesPage.expectRateInEditForm(rate.identifier, "22");
    });

    test("should delete a tax rate and remove it from the grid", async () => {
        const rate = generateTaxRateData();
        const untouched = generateTaxRateData();
        created.push(rate.identifier, untouched.identifier);

        await taxRatesPage.createTaxRate(rate);
        await taxRatesPage.createTaxRate(untouched);
        await taxRatesPage.deleteTaxRate(rate.identifier);

        await taxRatesPage.expectTaxRateAbsent(rate.identifier);
        await taxRatesPage.expectTaxRateListed(untouched);
    });
});
