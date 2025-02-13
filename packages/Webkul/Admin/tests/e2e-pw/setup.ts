import { test as base, expect, type Page } from "@playwright/test";

type AdminFixtures = {
    adminPage: Page;
};

export const test = base.extend<AdminFixtures>({
    adminPage: async ({ page }, use) => {
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        await page.goto("admin/login");
        await page.fill('input[name="email"]', adminCredentials.email);
        await page.fill('input[name="password"]', adminCredentials.password);
        await page.press('input[name="password"]', "Enter");

        await page.waitForURL("**/admin/dashboard");

        await use(page);
    },
});

export { expect };
