import { test as base, expect, type Page } from '@playwright/test';

type AdminFixtures = {
    adminPage: Page;
};

const config = {
    baseUrl: process.env.APP_URL,

    adminEmail: process.env.ADMIN_EMAIL || 'admin@example.com',

    adminPassword: process.env.ADMIN_PASSWORD || 'admin123',

    browser: process.env.BROWSER || 'chromium',
};

export const test = base.extend<AdminFixtures>({
    adminPage: async ({ page }, use) => {
        await page.goto(`${config.baseUrl}/admin/login`);
        await page.fill('input[name="email"]', config.adminEmail);
        await page.fill('input[name="password"]', config.adminPassword);
        await page.press('input[name="password"]', 'Enter');

        await page.waitForURL('**/admin/dashboard');

        await use(page);
    },
});

export { expect, config };
