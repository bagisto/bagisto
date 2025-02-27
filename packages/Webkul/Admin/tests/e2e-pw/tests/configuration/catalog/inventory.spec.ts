import { test, expect } from '../../../setup';
import { generateRandomNumericString } from '../../../utils/faker';


test.describe('Inventory Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/catalog/inventory');
    });

    /**
     * Update the Inventory Configuration.
     */
    test('should allow back orders and define out-of-stock thresholds', async ({ adminPage }) => {
        await adminPage.click('label[for="catalog[inventory][stock_options][back_orders]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[inventory][stock_options][back_orders]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.locator('input[name="catalog[inventory][stock_options][out_of_stock_threshold]"]').fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
