import { test, expect } from "../../../setup";
import { getImageFile } from "../../../utils/faker";
import { DesignConfigurationPage } from "../../../pages/admin/configuration/general/DesignConfigurationPage";

test.describe("design configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new DesignConfigurationPage(adminPage).open();
    });

    test("should update and delete the logo", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);
        await page.uploadLogo(getImageFile());
        await page.saveAndVerify();
        await page.deleteLogo();
        await page.saveAndVerify();
    });

    test("should update and delete favicon", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);
        await page.uploadFavicon(getImageFile());
        await page.saveAndVerify();
        await page.deleteFavicon();
        await page.saveAndVerify();
    });

    test("should Set sidebar Menu category view", async ({ adminPage }) => {
        const page = new DesignConfigurationPage(adminPage);

        await page.selectCategoryView("sidebar");
        await page.previewSidebarMenu();
        await page.saveAndVerify();

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
        const page = new DesignConfigurationPage(adminPage);

        await page.selectCategoryView("default");
        await page.previewDefaultMenu();
        await page.saveAndVerify();

        await adminPage.goto("");
        await expect(adminPage.getByText("Men").first()).toBeVisible();
        await adminPage.waitForTimeout(2000);
        await adminPage.hover('a:has-text("Men")');
        await adminPage.waitForTimeout(2000);
        await expect(
            adminPage.getByRole("link", { name: "Formal Wear" }).first(),
        ).toBeVisible();
    });
});
