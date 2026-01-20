import { test, expect } from "../../../setup";

/**
 * Project ID for Google Captcha.
 */
const projectID = 'bagisto-test-project';

/**
 * API Key for Google Captcha.
 */
const apiKey = 'AIzaSyD-EXAMPLEKEY1234567890';

/**
 * Site Key for Google Captcha.
 */
const siteKey = '6LcEXAMPLEKEY1234567890';

test.describe("google captcha configuration", () => {
    /**
     * Disable the google captcha configuration as this will create issue in further testcases.
     */
    test.afterEach(async ({ adminPage }) => {
        /**
         * Navigate to the configuration page.
         */
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

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator('p.flex.items-center.break-all.text-sm', { hasText: "Configuration saved successfully" })
        ).toBeVisible();

        await expect(
            adminPage.locator(
                'input[type="checkbox"][name="customer[captcha][credentials][status]"]'
            )
        ).not.toBeChecked();
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
            .locator('input[name="customer[captcha][credentials][project_id]"]')
            .fill(projectID);
        
        await adminPage
            .locator('input[name="customer[captcha][credentials][api_key]"]')
            .fill(apiKey);

        await adminPage
            .locator('input[name="customer[captcha][credentials][site_key]"]')
            .fill(siteKey);

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator('p.flex.items-center.break-all.text-sm', { hasText: "Configuration saved successfully" })
        ).toBeVisible();

        await expect(
            adminPage.locator(
                'input[type="checkbox"][name="customer[captcha][credentials][status]"]'
            )
        ).toBeChecked();

        await expect(
            adminPage.locator(
                'input[name="customer[captcha][credentials][project_id]"]'
            )
        ).toHaveValue(projectID);

        await expect(
            adminPage.locator(
                'input[name="customer[captcha][credentials][api_key]"]'
            )
        ).toHaveValue(apiKey);

        await expect(
            adminPage.locator(
                'input[name="customer[captcha][credentials][site_key]"]'
            )
        ).toHaveValue(siteKey);
    });
});
