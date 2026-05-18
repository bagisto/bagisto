import { test } from "../../../setup";
import { generateDescription } from "../../../utils/faker";
import { ShippingMethodsConfigurationPage } from "../../../pages/admin/configuration/sales/ShippingMethodsConfigurationPage";

test.describe("shipping methods configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new ShippingMethodsConfigurationPage(adminPage).open();
    });

    test("should configure the free shipping method", async ({ adminPage }) => {
        const page = new ShippingMethodsConfigurationPage(adminPage);

        await page.configureFreeShipping(generateDescription(200));
        await page.saveAndVerify();
    });

    test("should configure the flat rate shipping method", async ({
        adminPage,
    }) => {
        const page = new ShippingMethodsConfigurationPage(adminPage);

        await page.configureFlatRate("per_unit");
        await page.saveAndVerify();
    });
});
