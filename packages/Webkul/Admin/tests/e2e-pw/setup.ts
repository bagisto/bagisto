import { test as base, expect, type Page } from "@playwright/test";
import fs from "fs";
import { ADMIN_AUTH_STATE_PATH } from "./playwright.config";

interface AdminPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

type AdminFixtures = {
    adminPage: AdminPage;
};

export const test = base.extend<AdminFixtures>({
    adminPage: async ({ browser }, use) => {
        const authExists = fs.existsSync(ADMIN_AUTH_STATE_PATH);

        const context = await browser.newContext(
            authExists ? { storageState: ADMIN_AUTH_STATE_PATH } : {}
        );

        const page = await context.newPage();

        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        if (!authExists) {
            /**
             * Authenticate the admin user.
             */
            await page.goto("admin/login");
            await page.fill('input[name="email"]', adminCredentials.email);
            await page.fill(
                'input[name="password"]',
                adminCredentials.password
            );
            await page.press('input[name="password"]', "Enter");

            /**
             * Wait for the dashboard to load.
             */
            await page.waitForURL("**/admin/dashboard");

            /**
             * Save authentication state to a file.
             */
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        }

        /**
         * Navigate to the dashboard.
         */
        await page.goto("admin/dashboard");

        /**
         * Extend the page object with custom methods.
         */
        (page as AdminPage).fillInTinymce = async function (
            iframeSelector: string,
            content: string
        ) {
            await page.waitForSelector(iframeSelector);
            const iframe = page.frameLocator(iframeSelector);
            const editorBody = iframe.locator("body");
            await editorBody.click();
            await editorBody.pressSequentially(content);
            await expect(editorBody).toHaveText(content);
        };

        await use(page as AdminPage);
        await context.close();
    },
});

export { expect };
