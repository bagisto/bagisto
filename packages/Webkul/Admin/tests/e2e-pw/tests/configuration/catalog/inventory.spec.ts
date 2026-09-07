import { test } from "../../../setup";
import {
    InventoryConfigurationPage,
    type InventorySettings,
} from "../../../pages/admin/configuration/catalog/InventoryConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("inventory configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: InventoryConfigurationPage;
    let original: InventorySettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new InventoryConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the back order and out of stock threshold settings after reload", async () => {
        const changed = {
            backOrders: !original.backOrders,
            outOfStockThreshold: other(original.outOfStockThreshold, "3", "5"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
