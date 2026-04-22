import { test, expect } from "../../../setup";

test.describe("email notification configuration", () => {
    test("should configure the email settings", async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/emails/general");
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][verification]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][registration]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][customer_registration_confirmation_mail_to_admin]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][customer_account_credentials]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_order]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_order_mail_to_admin]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_invoice]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_invoice_mail_to_admin]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_refund]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_refund_mail_to_admin]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_shipment]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_shipment_mail_to_admin]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][new_inventory_source]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][cancel_order]"]');
        // await adminPage.click('label[for="emails[general][notifications][emails][general][notifications][cancel_order_mail_to_admin]"]');
        await adminPage.click('button[type="submit"].primary-button:visible');
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
