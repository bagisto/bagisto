import { test, expect } from "../../../setup";
import { getImageFile } from "../../../utils/faker";

test.describe("design configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/general/design");
    });

    test("should update and delete the logo", async ({ adminPage }) => {
        /**
         * Target the only logo input field.
         */
        const logoInput = await adminPage.$(
            'input[type="file"][name="general[design][admin_logo][logo_image]"]'
        );

        if (logoInput) {
            const filePath = getImageFile();
            await logoInput.setInputFiles(filePath);
        } else {
            throw new Error("Logo input field not found");
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
       await expect(adminPage.locator('#app p' , { hasText: 'Configuration saved successfully' })).toBeVisible();

        /**
         * Delete the uploaded logo.
         */
        await adminPage
            .locator(
                '[id="general\\[design\\]\\[admin_logo\\]\\[logo_image\\]\\[delete\\]"]'
            )
            .nth(1)
            .click();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.locator('#app p' , { hasText: 'Configuration saved successfully' })).toBeVisible();
    });

    test("should update and delete favicon", async ({ adminPage }) => {
        /**
         * Target the only favicon input field.
         */
        const faviconInput = await adminPage.$(
            'input[type="file"][name="general[design][admin_logo][favicon]"]'
        );

        if (faviconInput) {
            const filePath = getImageFile();
            await faviconInput.setInputFiles(filePath);
        } else {
            throw new Error("Favicon input field not found");
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();

        /**
         * Delete the uploaded favicon.
         */
        await adminPage
            .locator(
                '[id="general\\[design\\]\\[admin_logo\\]\\[favicon\\]\\[delete\\]"]'
            )
            .nth(1)
            .click();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.getByText("Configuration saved successfully")
        ).toBeVisible();
    });

    test("should Set sidebar Menu category view", async ({ adminPage }) => {
        /**
         * Select the default menu category view.
         */
        await adminPage.selectOption(
            '[name="general[design][categories][category_view]"]',
            "sidebar"
        );
        await adminPage
            .getByRole("button", { name: " Preview Sidebar Menu " })
            .click();
         await expect(
            adminPage.locator(".flex.items-center.justify-between.gap-2\\.5")
        ).toBeVisible();

        await adminPage.locator(".icon-cancel-1").click();

        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();

        /**
         * chekk whether the sidebar menu category view is set or not.
         */
        await adminPage.goto("");
        await expect(adminPage.getByText("All", { exact: true })).toBeVisible();
        await adminPage
            .locator("#app span")
            .filter({ hasText: "All" })
            .locator("span")
            .click();
        await adminPage.locator(".icon-cancel").first().click();
    });
    test("should Set default Menu category view", async ({ adminPage }) => {
        /**
         * Select the default menu category view.
         */
        await adminPage.selectOption(
            '[name="general[design][categories][category_view]"]',
            "default"
        );

        await adminPage
            .getByRole("button", { name: "Preview Default Menu" })
            .click();
        await expect(
            adminPage.locator(".flex.items-center.justify-between.gap-2\\.5")
        ).toBeVisible();

        await adminPage.locator(".icon-cancel-1").click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();

        /**
         * Chekk whether the menu category view is set to default or not.
         */

        await adminPage.goto("");
        await expect(adminPage.getByText("Men").first()).toBeVisible();
        await adminPage.waitForTimeout(2000);
        await adminPage.hover('a:has-text("Men")');
        await adminPage.waitForTimeout(2000);
        await expect(
            adminPage.getByRole("link", { name: "Winter Wear" }).first()
        ).toBeVisible();
    });
});
