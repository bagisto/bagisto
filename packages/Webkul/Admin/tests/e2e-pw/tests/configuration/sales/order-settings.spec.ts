import { test, expect } from '../../../setup';
import { 
    generateName, 
    generateRandomNumericString,
} from '../../../utils/faker';

test.describe('Order Settings Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/order_settings');
    });

    /**
     * Update the Order Number Configuration.
     */
    test('should update order number settings', async ({ adminPage }) => {
        await adminPage.fill('input[name="sales[order_settings][order_number][order_number_prefix]"]', generateName());
        await adminPage.fill('input[name="sales[order_settings][order_number][order_number_length]"]', generateRandomNumericString(1, 10));
        await adminPage.fill('input[name="sales[order_settings][order_number][order_number_suffix]"]', generateName());
        await adminPage.fill('input[name="sales[order_settings][order_number][order_number_generator]"]', generateRandomNumericString(2));

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Minimum Order Settings Configuration.
     */
    test('should update minimum order settings', async ({ adminPage }) => {
        await adminPage.click('label[for="sales[order_settings][minimum_order][enable]"]');
        const minimumOrderToggle = await adminPage.locator('input[name="sales[order_settings][minimum_order][enable]"]');
        // await expect(minimumOrderToggle).toBeChecked();

        // if (await minimumOrderToggle.toBeChecked()) {
        //     await adminPage.fill('number[name="sales[order_settings][minimum_order][minimum_order_amount]"]', generateRandomNumericString(2));

        //     await adminPage.click('label[for="sales[order_settings][minimum_order][include_discount_amount]"]');
        //     const minimumOrderAmountToggle = await adminPage.locator('input[name="sales[order_settings][minimum_order][include_discount_amount]"]');
        //     // await expect(minimumOrderAmountToggle).toBeChecked();

        //     await adminPage.click('label[for="sales[order_settings][minimum_order][include_tax_to_amount]"]');
        //     const includeTaxAmountToggle = await adminPage.locator('input[name="sales[order_settings][minimum_order][include_tax_to_amount]"]');
        //     // await expect(includeDiscountAmountToggle).toBeChecked();

        //     await adminPage.fill('textarea[name="sales[order_settings][minimum_order][description]"]', generateDescription(200));
        // }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Reorder Configuration.
     */
    test('should update reorder settings', async ({ adminPage }) => {
        await adminPage.click('label[for="sales[order_settings][reorder][admin]"]');
        const adminReorderToggle = await adminPage.locator('input[name="sales[order_settings][reorder][admin]"]');
        // await expect(adminReorderToggle).toBeChecked();

        await adminPage.click('label[for="sales[order_settings][reorder][shop]"]');
        const shopReorderToggle = await adminPage.locator('input[name="sales[order_settings][reorder][shop]"]');
        // await expect(shopReorderToggle).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
