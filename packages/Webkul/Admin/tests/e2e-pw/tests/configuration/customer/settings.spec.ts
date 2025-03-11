import { test, expect } from "../../../setup";

test.describe("settings configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/customer/settings");
    });

    test("should enable the wishlist feature", async ({ adminPage }) => {
        await adminPage.click(
            'label[for="customer[settings][wishlist][wishlist_option]"]'
        );

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });

    test("should update the redirect page option after the login", async ({
        adminPage,
    }) => {
        await adminPage.selectOption(
            'select[name="customer[settings][login_options][redirected_to_page]"]',
            "home"
        );
        const weightUnitSelect = adminPage.locator(
            'select[name="customer[settings][login_options][redirected_to_page]"]'
        );
        await expect(weightUnitSelect).toHaveValue("home");

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });

    test("should update default customer group and enabling the newsletter subscription option during sign-up", async ({
        adminPage,
    }) => {
        await adminPage.selectOption(
            'select[name="customer[settings][create_new_account_options][default_group]"]',
            "general"
        );
        const defaultGroup = adminPage.locator(
            'select[name="customer[settings][create_new_account_options][default_group]"]'
        );
        await expect(defaultGroup).toHaveValue("general");

        await adminPage.click(
            'label[for="customer[settings][create_new_account_options][news_letter]"]'
        );

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });

    test("should update the newsletter subscription option", async ({
        adminPage,
    }) => {
        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="customer[settings][newsletter][subscription]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="customer[settings][newsletter][subscription]"]'
            );
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();

        await expect(
            await adminPage.locator(
                'input[type="checkbox"][name="customer[settings][newsletter][subscription]"]'
            )
        ).toBeChecked();
    });

    test("should enable the social login option", async ({ adminPage }) => {
        await adminPage.click(
            'label[for="customer[settings][social_login][enable_facebook]"]'
        );

        await adminPage.click(
            'label[for="customer[settings][social_login][enable_twitter]"]'
        );

        await adminPage.click(
            'label[for="customer[settings][social_login][enable_google]"]'
        );

        await adminPage.click(
            'label[for="customer[settings][social_login][enable_linkedin-openid]"]'
        );

        await adminPage.click(
            'label[for="customer[settings][social_login][enable_github]"]'
        );

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });
});
