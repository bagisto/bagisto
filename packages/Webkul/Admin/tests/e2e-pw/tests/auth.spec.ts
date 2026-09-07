import { test } from "../setup";
import { LoginPage } from "../pages/admin/auth/LoginPage";
import { env } from "../utils/env";

test.describe("admin authentication", () => {
    test("should sign in with valid credentials and reach the dashboard", async ({
        page,
    }) => {
        const loginPage = new LoginPage(page);

        await loginPage.login(env.adminEmail, env.adminPassword);
    });

    test("should refuse a wrong password", async ({ page }) => {
        const loginPage = new LoginPage(page);

        await loginPage.attemptLogin(env.adminEmail, `${env.adminPassword}x`);

        await loginPage.expectLoginRefused(
            "Please check your credentials and try again.",
        );
        await loginPage.expectDashboardRequiresLogin();
    });

    test("should end the session on logout", async ({ page }) => {
        const loginPage = new LoginPage(page);

        await loginPage.login(env.adminEmail, env.adminPassword);
        await loginPage.logout();

        await loginPage.expectDashboardRequiresLogin();
    });
});
