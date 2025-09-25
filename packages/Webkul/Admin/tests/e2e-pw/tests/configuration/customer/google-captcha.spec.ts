import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";

test.describe("google captcha configuration", () => {
    /**
     * Disable the google captcha configuration as this will create issue in further testcases.
     */
    test.afterEach(async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/customer/captcha");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="customer[captcha][credentials][status]"]'
            )
            .isChecked();

        if (isChecked) {
            await adminPage.click(
                'label[for="customer[captcha][credentials][status]"]'
            );
        }

        await adminPage.click('button[type="submit"].primary-button:visible');
    });

    test("should make enable the google captcha with site and secret key", async ({
        adminPage,
    }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/customer/captcha");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="customer[captcha][credentials][status]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="customer[captcha][credentials][status]"]'
            );
        }

        await adminPage
            .locator('input[name="customer[captcha][credentials][site_key]"]')
            .fill(generateDescription(60));
        await adminPage
            .locator('input[name="customer[captcha][credentials][secret_key]"]')
            .fill(generateDescription(60));

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
