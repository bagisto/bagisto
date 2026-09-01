import {
    test as base,
    expect,
    type BrowserContext,
    type Page,
} from "@playwright/test";
import fs from "fs";
import { loginAsAdmin } from "./utils/admin";
import { ADMIN_AUTH_STATE_PATH, ensureStateDir } from "./utils/paths";

interface AdminPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

interface ShopPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

type Fixtures = {
    adminPage: AdminPage;
    shopPage: ShopPage;
};

async function saveAdminAuth(context: BrowserContext): Promise<void> {
    ensureStateDir();

    await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
}

export const test = base.extend<Fixtures>({
    adminPage: async ({ browser }, use) => {
        const authExists = fs.existsSync(ADMIN_AUTH_STATE_PATH);

        const context = await browser.newContext(
            authExists ? { storageState: ADMIN_AUTH_STATE_PATH } : {},
        );

        const page = await context.newPage();

        if (!authExists) {
            await loginAsAdmin(page);
            await saveAdminAuth(context);
        } else {
            await page.goto("admin/dashboard");
        }

        if (page.url().includes("admin/login")) {
            await loginAsAdmin(page);
            await saveAdminAuth(context);
        }

        (page as AdminPage).fillInTinymce = async (
            iframeSelector: string,
            content: string,
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

    shopPage: async ({ browser }, use) => {
        const context = await browser.newContext();
        const page = await context.newPage();

        (page as ShopPage).fillInTinymce = async (
            iframeSelector: string,
            content: string,
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
