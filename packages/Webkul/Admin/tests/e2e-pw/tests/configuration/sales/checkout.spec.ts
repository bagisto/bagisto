import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    CheckoutConfigurationPage,
    type CheckoutSettings,
} from "../../../pages/admin/configuration/sales/CheckoutConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("checkout configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: CheckoutConfigurationPage;
    let original: CheckoutSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new CheckoutConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the shopping cart settings after reload", async () => {
        const changed = {
            guestCheckout: !original.guestCheckout,
            cartPage: !original.cartPage,
            crossSell: !original.crossSell,
            estimateShipping: !original.estimateShipping,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the mini cart settings after reload", async () => {
        const changed = {
            miniCart: !original.miniCart,
            miniCartSummary: other(
                original.miniCartSummary,
                "display_item_quantity",
                "display_number_of_items_in_cart",
            ),
            miniCartOffer: `Offer ${uniqueStamp()}`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
