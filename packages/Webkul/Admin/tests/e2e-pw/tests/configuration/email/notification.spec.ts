import { test, expect } from '../../../setup';

test.describe('Email Settings Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/emails/general');
    });

    /**
     * Update the Google Captcha Configuration.
     */
    test('should configure the email settings', async ({ adminPage }) => {
        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][verification]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][verification]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][registration]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][registration]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][customer_registration_confirmation_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][customer_registration_confirmation_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][customer_account_credentials]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][customer_account_credentials]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_order]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_order]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_order_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_order_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_invoice]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_invoice]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_invoice_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_invoice_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_refund]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_refund]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_refund_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_refund_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_shipment]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_shipment]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_shipment_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_shipment_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_inventory_source]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][new_inventory_source]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][cancel_order]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][cancel_order]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][cancel_order_mail_to_admin]"]');
        // const toggleInput = await adminPage.locator('input[name="emails[general][notifications][emails][general][notifications][cancel_order_mail_to_admin]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
