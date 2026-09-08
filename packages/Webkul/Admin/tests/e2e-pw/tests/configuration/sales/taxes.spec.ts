import { test } from "../../../setup";
import {
    TaxConfigurationPage,
    type TaxSettings,
} from "../../../pages/admin/configuration/sales/TaxConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("taxes configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: TaxConfigurationPage;
    let original: TaxSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new TaxConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the tax calculation settings after reload", async () => {
        const changed = {
            basedOn: other(original.basedOn, "shipping_address", "billing_address"),
            productPrices: other(original.productPrices, "excluding_tax", "including_tax"),
            shippingPrices: other(original.shippingPrices, "excluding_tax", "including_tax"),
            applyTaxOn: other(original.applyTaxOn, "after_discount", "before_discount"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the default destination after reload", async () => {
        const changed = {
            defaultCountry: "IN",
            defaultState: "UP",
            defaultPostcode: other(original.defaultPostcode, "201301", "201302"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the shopping cart display settings after reload", async () => {
        const changed = {
            cartDisplayPrices: other(original.cartDisplayPrices, "excluding_tax", "including_tax"),
            cartDisplaySubtotal: other(original.cartDisplaySubtotal, "excluding_tax", "including_tax"),
            cartDisplayShipping: other(original.cartDisplayShipping, "excluding_tax", "including_tax"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the orders, invoices and refunds display settings after reload", async () => {
        const changed = {
            salesDisplayPrices: other(original.salesDisplayPrices, "excluding_tax", "including_tax"),
            salesDisplaySubtotal: other(original.salesDisplaySubtotal, "excluding_tax", "including_tax"),
            salesDisplayShipping: other(original.salesDisplayShipping, "excluding_tax", "including_tax"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
