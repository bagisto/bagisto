import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    ShippingMethodsConfigurationPage,
    type ShippingMethodSettings,
} from "../../../pages/admin/configuration/sales/ShippingMethodsConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("shipping methods configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: ShippingMethodsConfigurationPage;
    let original: ShippingMethodSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new ShippingMethodsConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the free shipping details after reload", async () => {
        const stamp = uniqueStamp();
        const changed = {
            freeShippingTitle: `Free ${stamp}`,
            freeShippingDescription: `Free shipping ${stamp}`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the flat rate details after reload", async () => {
        const stamp = uniqueStamp();
        const changed = {
            flatRateTitle: `Flat ${stamp}`,
            flatRateDescription: `Flat rate ${stamp}`,
            flatRateDefaultRate: other(original.flatRateDefaultRate, "10", "15"),
            flatRateType: other(original.flatRateType, "per_unit", "per_order"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
