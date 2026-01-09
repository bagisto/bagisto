import { test as base, expect, type Page } from "@playwright/test";
import fs from "fs";
import { ADMIN_AUTH_STATE_PATH } from "./playwright.config";
import { loginAsAdmin } from "./utils/admin";

interface AdminPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

interface ShopPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

/**
 * Fixtures Types
 */

type Fixtures = {
    adminPage: AdminPage;
    shopPage: ShopPage;
};

/**
 * Test with Fixtures
 */

export const test = base.extend<Fixtures>({
    /**
     *  AdminPage
     */

    adminPage: async ({ browser }, use) => {
        const authExists = fs.existsSync(ADMIN_AUTH_STATE_PATH);

        const context = await browser.newContext(
            authExists ? { storageState: ADMIN_AUTH_STATE_PATH } : {}
        );

        const page = await context.newPage();

        // Login if needed
        if (!authExists) {
            await loginAsAdmin(page);
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        } else {
            await page.goto("admin/dashboard");
        }

        // Safety check (session expired)
        if (page.url().includes("admin/login")) {
            await loginAsAdmin(page);
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        }

        // Extend admin page
        (page as AdminPage).fillInTinymce = async (
            iframeSelector: string,
            content: string
        ) => {
            await page.waitForSelector(iframeSelector);

            const iframe = page.frameLocator(iframeSelector);
            const editorBody = iframe.locator("body");

            await expect(editorBody).toBeVisible();
            await editorBody.focus();
            await editorBody.press("Control+a");
            await editorBody.press("Backspace");

            await editorBody.pressSequentially(content);
            await expect(editorBody).toHaveText(content);
        };

        await use(page as AdminPage);
        await context.close();
    },

    /**
     * Shop Page
     */

    shopPage: async ({ browser }, use) => {
        const context = await browser.newContext();
        const page = await context.newPage();

        /**
         * Extend shop page with Tinymce helper
         * (exact logic you provided)
         */
        (page as ShopPage).fillInTinymce = async (
            iframeSelector: string,
            content: string
        ) => {
            await page.waitForSelector(iframeSelector);
            const iframe = page.frameLocator(iframeSelector);
            const editorBody = iframe.locator("body");
            await editorBody.click();
            await editorBody.pressSequentially(content);
            await expect(editorBody).toHaveText(content);
        };

        await use(page as ShopPage);
        await context.close();
    },
});

export { expect };
