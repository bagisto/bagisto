import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    ShippingSettingsConfigurationPage,
    type ShippingOriginSettings,
} from "../../../pages/admin/configuration/sales/ShippingSettingsConfigurationPage";

test.describe("shipping settings configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: ShippingSettingsConfigurationPage;
    let original: ShippingOriginSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new ShippingSettingsConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the shipping origin after reload", async () => {
        const stamp = uniqueStamp();
        const changed = {
            country: "IN",
            state: "UP",
            city: `Noida ${stamp}`,
            address: `Sector 62 ${stamp}`,
            zipcode: "201301",
            storeName: `Store ${stamp}`,
            vatNumber: `VAT${stamp}`,
            contact: "9876543210",
            bankDetails: `Bank details ${stamp}`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
