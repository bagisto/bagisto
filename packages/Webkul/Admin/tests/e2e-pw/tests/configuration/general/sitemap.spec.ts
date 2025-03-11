import { test, expect } from "../../../setup";

async function enableSitemap(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/sitemap");

    const isEnabled = adminPage.locator("label > div").check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator("label > div");
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").click();
    }

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).toBeChecked();
}

async function disableSitemap(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");

    const isDisabled = adminPage.locator("label > div").uncheck();

    /**
     * If not disabled, then we disable it.
     */
    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator("label > div");
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").click();
    }

    /**
     * Verifying disable state.
     */
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).not.toBeChecked();
}

test.describe("sitemap configuration", () => {
    test("should validate sitemap priority condition between 0 to 1", async ({
        adminPage,
    }) => {
        await enableSitemap(adminPage);

        await adminPage
            .locator('[id="general\\[sitemap\\]\\[store_url\\]\\[priority\\]"]')
            .click();
        await adminPage
            .locator(
                '[id="general\\[sitemap\\]\\[store_url\\]\\[frequency\\]"]'
            )
            .selectOption("weekly");
        await adminPage
            .locator('[id="general\\[sitemap\\]\\[store_url\\]\\[priority\\]"]')
            .fill("0.7");
        await adminPage
            .locator(
                '[id="general\\[sitemap\\]\\[categories\\]\\[priority\\]"]'
            )
            .click();
        await adminPage
            .locator(
                '[id="general\\[sitemap\\]\\[categories\\]\\[frequency\\]"]'
            )
            .selectOption("hourly");
        await adminPage
            .locator(
                '[id="general\\[sitemap\\]\\[categories\\]\\[priority\\]"]'
            )
            .fill("0.6");

        await adminPage
            .locator('[id="general\\[sitemap\\]\\[products\\]\\[priority\\]"]')
            .click();
        await adminPage
            .locator('[id="general\\[sitemap\\]\\[products\\]\\[frequency\\]"]')
            .selectOption("hourly");
        await adminPage
            .locator('[id="general\\[sitemap\\]\\[products\\]\\[priority\\]"]')
            .fill("0.5");
        await adminPage
            .locator('[id="general\\[sitemap\\]\\[cms\\]\\[priority\\]"]')
            .click();
        await adminPage
            .locator('[id="general\\[sitemap\\]\\[cms\\]\\[frequency\\]"]')
            .selectOption("hourly");

        await adminPage
            .locator('[id="general\\[sitemap\\]\\[cms\\]\\[priority\\]"]')
            .fill("0.6");

        /**
         * Click the 'Save Configuration' button.
         */
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();

        /*
         * Verify message.
         */
        const successMessage = adminPage.getByText(
            "Configuration saved successfully Close"
        );
        await successMessage.waitFor({ state: "visible" });
        await expect(adminPage.locator("#app")).not.toHaveText(
            "The Priority field may not be greater than 1 characters"
        );
    });

    test("should disable the sitemap for your website when sitemap button is disabled", async ({
        adminPage,
    }) => {
        await disableSitemap(adminPage);

        /**
         * Click the 'Save Configuration' button.
         */
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();

        /*
         * Verify success message
         */
        const successMessage = adminPage.getByText(
            "Configuration saved successfully Close"
        );
        await successMessage.waitFor({ state: "visible" });
        const toggleInput = adminPage.locator("label > div").first();
        await expect(toggleInput).not.toBeChecked();
    });

    test("should set maximum number of urls per file", async ({
        adminPage,
    }) => {
        await enableSitemap(adminPage);

        await adminPage
            .getByRole("textbox", { name: "Maximum no. of URLs per file" })
            .click();
        await adminPage
            .getByRole("textbox", { name: "Maximum no. of URLs per file" })
            .fill("4000");

        /**
         * Click the 'Save Configuration' button.
         */
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();

        /*
         * Verify success message
         */
        const successMessage = adminPage.getByText(
            "Configuration saved successfully Close"
        );
        await successMessage.waitFor({ state: "visible" });
        await expect(
            adminPage.getByRole("textbox", {
                name: "Maximum no. of URLs per file",
            })
        ).toHaveValue("4000");
    });
});
