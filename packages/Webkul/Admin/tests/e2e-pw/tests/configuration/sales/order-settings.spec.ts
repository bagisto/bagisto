import { test, expect } from "../../../setup";
import { generateName } from "../../../utils/faker";
import { OrderSettingsConfigurationPage } from "../../../pages/admin/configuration/sales/OrderSettingsConfigurationPage";

test.describe("order settings configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new OrderSettingsConfigurationPage(adminPage).open();
    });

    test("should update order number settings", async ({ adminPage }) => {
        const page = new OrderSettingsConfigurationPage(adminPage);

        await page.fillOrderNumberSettings(generateName(), "5", generateName());
        await page.saveAndVerify();
    });

    test("should update minimum order settings", async ({ adminPage }) => {
        const page = new OrderSettingsConfigurationPage(adminPage);

        await page.saveAndVerify();
    });

    test("should update reorder settings", async ({ adminPage }) => {
        const page = new OrderSettingsConfigurationPage(adminPage);

        await page.enableReorderOptions();
        await page.saveAndVerify();
    });
});
