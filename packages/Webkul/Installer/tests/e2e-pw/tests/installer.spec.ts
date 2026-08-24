import { test, expect } from "../setup";
import { InstallerPage } from "../pages/InstallerPage";

test.describe("guided installation", () => {
    test("should install Bagisto through the guided web installer in English @en", async ({ page }) => {
        const installer = new InstallerPage(page);

        await installer.install("en");

        await expect(page.locator("html")).toHaveAttribute("dir", "ltr");
        await expect(page.locator('a[href*="/admin/login"]')).toBeVisible();
    });

    test("should install Bagisto through the guided web installer in Arabic @ar", async ({ page }) => {
        const installer = new InstallerPage(page);

        await installer.install("ar");

        await expect(page.locator("html")).toHaveAttribute("dir", "rtl");
        await expect(page.locator('a[href*="/admin/login"]')).toBeVisible();
    });
});
