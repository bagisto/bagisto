import { test, expect } from "../../../setup";
import { generateRandomNumericString } from "../../../utils/faker";

test.describe("inventory configuration", () => {
    test("should allow back orders and define out-of-stock thresholds", async ({
        adminPage,
    }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/catalog/inventory");

        await adminPage.click(
            'label[for="catalog[inventory][stock_options][back_orders]"]'
        );

        await adminPage
            .locator(
                'input[name="catalog[inventory][stock_options][out_of_stock_threshold]"]'
            )
            .fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
