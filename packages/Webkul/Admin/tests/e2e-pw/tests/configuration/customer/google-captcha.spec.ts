import { test, expect } from '../../../setup';
import { generateRandomStringWithSpaces } from '../../../utils/form';


test.describe('Google Captcha Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/captcha');
    });

    /**
     * Update the Google Captcha Configuration.
     */
    test('should make enable the google captcha with site and secrete key', async ({ adminPage }) => {
        await adminPage.locator('input[name="customer[captcha][credentials][site_key]"]').fill(generateRandomStringWithSpaces(60));
        await adminPage.locator('input[name="customer[captcha][credentials][secret_key]"]').fill(generateRandomStringWithSpaces(60));

        await adminPage.click('label[for="customer[captcha][credentials][status]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[captcha][credentials][status]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
