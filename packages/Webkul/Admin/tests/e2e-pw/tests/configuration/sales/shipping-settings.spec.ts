import { test, expect } from '../../../setup';
import { generateRandomNumericString, generateDescription } from '../../../utils/faker';
import * as forms from "../../../utils/form";

test.describe('Shipping Settings Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/shipping');
    });

    /**
     * Update the Shipping Origin Configuration.
     */
    test('should add Shipping origin refers to the location where goods or products originate before being transported to their destination', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[shipping][origin][country]"]', 'IN');
        const country = adminPage.locator('select[name="sales[shipping][origin][country]"]');
        await expect(country).toHaveValue('IN');

        await adminPage.selectOption('select[name="sales[shipping][origin][state]"]', 'UP');
        const state = adminPage.locator('select[name="sales[shipping][origin][state]"]');
        await expect(state).toHaveValue('UP');

        await adminPage.fill('input[name="sales[shipping][origin][city]"]', forms.form.lastName);
        await adminPage.fill('input[name="sales[shipping][origin][address]"]', forms.form.firstName);
        await adminPage.fill('input[name="sales[shipping][origin][zipcode]"]', generateRandomNumericString(6));
        await adminPage.fill('input[name="sales[shipping][origin][store_name]"]', forms.form.firstName);
        await adminPage.fill('input[name="sales[shipping][origin][vat_number]"]', generateRandomNumericString(6));
        await adminPage.fill('input[name="sales[shipping][origin][contact]"]', forms.form.phone);
        await adminPage.fill('textarea[name="sales[shipping][origin][bank_details]"]', generateDescription(200));
        
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
