import { test, expect } from '../../../setup';
import { generateRandomNumericString, generateDescription, generateLastName, generateFirstName, generatePhoneNumber } from '../../../utils/faker';
test.describe('shipping settings configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/shipping');
    });

    /**
     * Update the Shipping Origin Configuration.
     */
    test('should add shipping origin refers to the location where goods or products originate before being transported to their destination', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[shipping][origin][country]"]', 'IN');
        const country = adminPage.locator('select[name="sales[shipping][origin][country]"]');
        await expect(country).toHaveValue('IN');

        await adminPage.selectOption('select[name="sales[shipping][origin][state]"]', 'UP');
        const state = adminPage.locator('select[name="sales[shipping][origin][state]"]');
        await expect(state).toHaveValue('UP');

        await adminPage.fill('input[name="sales[shipping][origin][city]"]', generateLastName());
        await adminPage.fill('input[name="sales[shipping][origin][address]"]', generateFirstName());
        await adminPage.fill('input[name="sales[shipping][origin][zipcode]"]', generateRandomNumericString(6));
        await adminPage.fill('input[name="sales[shipping][origin][store_name]"]', generateFirstName());
        await adminPage.fill('input[name="sales[shipping][origin][vat_number]"]', generateRandomNumericString(6));
        await adminPage.fill('input[name="sales[shipping][origin][contact]"]', generatePhoneNumber());
        await adminPage.fill('textarea[name="sales[shipping][origin][bank_details]"]', generateDescription(200));
        
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
         await expect(adminPage.locator('#app p' , { hasText: 'Configuration saved successfully' })).toBeVisible();
    });
});
