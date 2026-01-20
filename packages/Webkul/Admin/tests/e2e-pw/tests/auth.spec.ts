import { test, expect } from "../setup";
import { loginAsAdmin } from "../utils/admin";

test("should be able to login", async ({ page }) => {
    await loginAsAdmin(page);

    await expect(page.getByPlaceholder("Mega Search").first()).toBeVisible();
});

test("should be able to logout", async ({ page }) => {
    await loginAsAdmin(page);

    await page.click("button:text('E')");
    await page.getByRole("link", { name: "Logout" }).click();

    await expect(page.getByPlaceholder("Password").first()).toBeVisible();
});