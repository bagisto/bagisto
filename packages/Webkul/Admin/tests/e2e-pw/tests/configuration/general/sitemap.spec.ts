import { test, expect } from "../../../setup";

async function enableSitemap(adminPage) {
    await adminPage.goto("admin/configuration/general/sitemap");

    const isEnabled = adminPage.locator("label > div").check();
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator("label > div");
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").click();
    }
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).toBeChecked();
}

async function disableSitemap(adminPage) {
    await adminPage.goto("admin/configuration/general/sitemap");

    const isDisabled = adminPage.locator("label > div").uncheck();
    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator("label > div");
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").click();
    }
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).not.toBeChecked();
}

test.describe("sitemap configuration", () => {
    test("should disable the sitemap for your website when sitemap button is disabled", async ({
        adminPage,
    }) => {
        await disableSitemap(adminPage);
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
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
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
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