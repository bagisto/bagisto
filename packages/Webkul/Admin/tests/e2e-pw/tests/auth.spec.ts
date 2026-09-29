import { test } from "../setup";
import { LoginPage } from "../pages/admin/auth/LoginPage";
import { TwoFactorPage } from "../pages/admin/auth/TwoFactorPage";
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

test.describe("admin two factor verification page", () => {
    test("should send an admin with two factor authentication off to the dashboard", async ({
        adminPage,
    }) => {
        const twoFactorPage = new TwoFactorPage(adminPage);

        await twoFactorPage.openVerificationPage();

        await twoFactorPage.expectSentToDashboard();
    });

    test("should send a signed out visitor to the login page", async ({ page }) => {
        const twoFactorPage = new TwoFactorPage(page);

        await twoFactorPage.openVerificationPage();

        await twoFactorPage.expectSentToLogin();
    });
});
