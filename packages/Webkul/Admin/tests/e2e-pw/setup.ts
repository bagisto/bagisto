import { test as base, expect, type Page } from "@playwright/test";

interface AdminPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

type AdminFixtures = {
    adminPage: AdminPage;
};

export const test = base.extend<AdminFixtures>({
    adminPage: async ({ page }, use) => {
        /**
         * Login as admin.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        await page.goto("admin/login");
        await page.fill('input[name="email"]', adminCredentials.email);
        await page.fill('input[name="password"]', adminCredentials.password);
        await page.press('input[name="password"]', "Enter");

        await page.waitForURL("**/admin/dashboard");

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
    },
});

export { expect };
