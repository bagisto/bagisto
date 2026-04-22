import { test, expect } from "../setup";
import { LoginPage } from "../pages/admin/auth/LoginPage";

test("should be able to login", async ({ page }) => {
    const loginPage = new LoginPage(page);
    await loginPage.login("admin@example.com", "admin123");
});

test("should be able to logout", async ({ page }) => {
    const loginPage = new LoginPage(page);
    await loginPage.login("admin@example.com", "admin123");
    await loginPage.logout();
});
