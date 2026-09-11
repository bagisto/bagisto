import { test as base, expect, type Page } from "@playwright/test";
import fs from "fs";
import { ADMIN_AUTH_STATE_PATH } from "./playwright.config";
import { loginAsAdmin } from "./utils/admin";

export interface AdminPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

export interface ShopPage extends Page {
    fillInTinymce: (iframeSelector: string, content: string) => Promise<void>;
}

type Fixtures = {
    adminPage: AdminPage;
    shopPage: ShopPage;
};

async function fillTinymce(
    page: Page,
    iframeSelector: string,
    content: string,
): Promise<void> {
    const editorId = iframeSelector.replace(/^#/, "").replace(/_ifr$/, "");

    await page.waitForFunction((id) => {
        const editor = (window as any).tinymce?.get(id);

        return !! editor && editor.initialized;
    }, editorId);

    await page.evaluate(({ id, value }) => {
        const editor = (window as any).tinymce.get(id);

        editor.setContent(value);
        editor.fire("keyup");
        editor.save();
    }, { id: editorId, value: content });

    await expect(page.frameLocator(iframeSelector).locator("body")).toHaveText(content);
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
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        } else {
            await page.goto("admin/dashboard");
        }

        if (page.url().includes("admin/login")) {
            await loginAsAdmin(page);
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        }

        (page as AdminPage).fillInTinymce = (
            iframeSelector: string,
            content: string,
        ) => fillTinymce(page, iframeSelector, content);

        await use(page as AdminPage);
        await context.close();
    },

    shopPage: async ({ browser }, use) => {
        const context = await browser.newContext();
        const page = await context.newPage();

        (page as ShopPage).fillInTinymce = (
            iframeSelector: string,
            content: string,
        ) => fillTinymce(page, iframeSelector, content);

        await use(page as ShopPage);
        await context.close();
    },
});

export { expect };
