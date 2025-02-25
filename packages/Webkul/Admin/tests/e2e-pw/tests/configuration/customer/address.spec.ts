import { test, expect } from '../../../setup';

test.describe('Customer Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/address');
    });

    /**
     * Update the Customer Address Configuration.
     */
    test('should make country, state and zip as a required field', async ({ adminPage }) => {
        await adminPage.click('label[for="customer[address][requirements][country]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[address][requirements][country]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[address][requirements][state]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[address][requirements][state]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[address][requirements][postcode]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[address][requirements][postcode]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
