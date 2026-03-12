import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";

test.describe("magic ai configuration", () => {
    test("should update the openai credential", async ({ adminPage }) => {
        /**
         * Enable Magic AI from the general settings page.
         */
        await adminPage.goto("admin/configuration/magic_ai/general");

        const isSettingsEnabled = await adminPage
            .locator(
                'input[type="checkbox"][name="magic_ai[general][settings][enabled]"]'
            )
            .isChecked();

        if (!isSettingsEnabled) {
            await adminPage.click(
                'label[for="magic_ai[general][settings][enabled]"]'
            );
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();

        await expect(
            await adminPage.locator(
                'input[type="checkbox"][name="magic_ai[general][settings][enabled]"]'
            )
        ).toBeChecked();

        /**
         * Fill the OpenAI API key on the providers page.
         */
        await adminPage.goto("admin/configuration/magic_ai/providers");

        await adminPage
            .locator('input[name="magic_ai[providers][openai][api_key]"]')
            .fill(generateDescription(20));

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });

    test("should manage content using ai", async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/magic_ai/admin_features");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="magic_ai[admin_features][text_generation][enabled]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="magic_ai[admin_features][text_generation][enabled]"]'
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
                'input[type="checkbox"][name="magic_ai[admin_features][text_generation][enabled]"]'
            )
        ).toBeChecked();
    });

    test("should enable image generation using ai", async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/magic_ai/admin_features");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="magic_ai[admin_features][image_generation][enabled]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="magic_ai[admin_features][image_generation][enabled]"]'
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
                'input[type="checkbox"][name="magic_ai[admin_features][image_generation][enabled]"]'
            )
        ).toBeChecked();
    });

    test("should enable review translation using ai", async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/magic_ai/storefront_features");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="magic_ai[storefront_features][review_translation][enabled]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="magic_ai[storefront_features][review_translation][enabled]"]'
            );
        }

        /**
         * Selecting the model.
         */
        await adminPage.selectOption(
            'select[name="magic_ai[storefront_features][review_translation][model]"]',
            "gemini-2.5-flash"
        );

        /**
         * Saving configuration.
         */
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();

        await expect(
            await adminPage.locator(
                'input[type="checkbox"][name="magic_ai[storefront_features][review_translation][enabled]"]'
            )
        ).toBeChecked();
    });

    test("should craft a personalized checkout message for customers using AI", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/magic_ai/storefront_features");

        const isChecked = await adminPage
            .locator(
                'input[type="checkbox"][name="magic_ai[storefront_features][checkout_message][enabled]"]'
            )
            .isChecked();

        if (!isChecked) {
            await adminPage.click(
                'label[for="magic_ai[storefront_features][checkout_message][enabled]"]'
            );
        }

        /**
         * Save the configuration.
         */
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();

        await expect(
            await adminPage.locator(
                'input[type="checkbox"][name="magic_ai[storefront_features][checkout_message][enabled]"]'
            )
        ).toBeChecked();
    });
});
