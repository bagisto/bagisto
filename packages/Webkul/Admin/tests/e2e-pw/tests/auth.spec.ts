import { test, expect } from "../setup";

const adminCredentials = {
    email: "admin@example.com",
    password: "admin123",
};

test("should be able to login", async ({ page }) => {
    await page.goto("admin/login");
    await page.getByPlaceholder("Email Address").click();
    await page.getByPlaceholder("Email Address").fill(adminCredentials.email);
    await page.getByPlaceholder("Password").click();
    await page.getByPlaceholder("Password").fill(adminCredentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    await expect(page.getByPlaceholder("Mega Search").first()).toBeVisible();
});

test("should be able to logout", async ({ page }) => {
    await page.goto("admin/login");
    await page.getByPlaceholder("Email Address").click();
    await page.getByPlaceholder("Email Address").fill(adminCredentials.email);
    await page.getByPlaceholder("Password").click();
    await page.getByPlaceholder("Password").fill(adminCredentials.password);
    await page.getByLabel("Sign In").click();
    await page.click("button:text('E')");
    await page.getByRole("link", { name: "Logout" }).click();
    await page.waitForTimeout(5000);

    await expect(page.getByPlaceholder("Password").first()).toBeVisible();
});
