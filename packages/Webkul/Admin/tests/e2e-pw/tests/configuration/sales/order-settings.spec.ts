import { test } from "../../../setup";
import {
    OrderSettingsConfigurationPage,
    type OrderSettings,
} from "../../../pages/admin/configuration/sales/OrderSettingsConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("order settings configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: OrderSettingsConfigurationPage;
    let original: OrderSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new OrderSettingsConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the order number format after reload", async () => {
        const changed = {
            orderNumberPrefix: other(original.orderNumberPrefix, "E2E", "ORD"),
            orderNumberLength: other(original.orderNumberLength, "6", "8"),
            orderNumberSuffix: other(original.orderNumberSuffix, "X", "Y"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the minimum order settings after reload", async () => {
        const changed = {
            minimumOrderEnabled: !original.minimumOrderEnabled,
            minimumOrderAmount: other(original.minimumOrderAmount, "50", "75"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the reorder settings after reload", async () => {
        const changed = {
            reorderInAdmin: !original.reorderInAdmin,
            reorderInShop: !original.reorderInShop,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
