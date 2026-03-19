import { test, expect } from "../setup";


test.describe('Paytm Payment Method Configuration', () => {

    test('should login to admin and enable Paytm payment method configuration', async ({ adminPage }, testInfo) => {
		await adminPage.goto('admin/configuration/sales/payment_methods');

		await adminPage.locator('[name="sales[payment_methods][paytm][title]"]').fill('Paytm');

		await adminPage.locator('[name="sales[payment_methods][paytm][description]"]').fill('Paytm Payment Gateway	');

		await adminPage.locator('[name="sales[payment_methods][paytm][merchant_id]"]').fill('Testin57449845514680');

		await adminPage.locator('[name="sales[payment_methods][paytm][merchant_key]"]').fill('lvx0Rfm_9Vt11DZp');

		const paytm_status = await adminPage.locator('[name="sales[payment_methods][paytm][active]"]').nth(1);

		if (!(await paytm_status.isChecked())) {
			await paytm_status.click();
		}

		await adminPage.getByRole('button', { name: ' Save Configuration ' }).click();

		await expect(adminPage.getByText('Configuration saved successfully').first()).toBeVisible();
    });


});
