import { test as base, expect, type Page } from "@playwright/test";
import fs from "fs";
import { ADMIN_AUTH_STATE_PATH } from "./playwright.config";
import { loginAsAdmin } from "./utils/admin";

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

        if (!authExists) {
            /**
             * Authenticate the admin user.
             */
            await loginAsAdmin(page);

            /**
             * Save authentication state to a file.
             */
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        } else {
            /**
             * Navigate to the dashboard.
             */
            await page.goto("admin/dashboard");
        }

        if (page.url().includes("admin/login")) {
            /**
             * Authenticate the admin user.
             */
            await loginAsAdmin(page);

            /**
             * Save authentication state to a file.
             */
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        }

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
            await editorBody.press("Control+a");
            await editorBody.press("Backspace");
            await editorBody.pressSequentially(content);
            await expect(editorBody).toHaveText(content);
        };

        await use(page as AdminPage);
        await context.close();
    },
});

export { expect };