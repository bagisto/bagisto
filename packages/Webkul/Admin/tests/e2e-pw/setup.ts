import {
    test as base,
    expect,
    type BrowserContext,
    type Page,
} from "@playwright/test";
import fs from "fs";
import { loginAsAdmin } from "./utils/admin";
import { ADMIN_AUTH_STATE_PATH, ensureStateDir } from "./utils/paths";

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

    await page.waitForFunction(
        (id) => {
            const editor = (window as any).tinymce?.get(id);

            return !!editor && editor.initialized;
        },
        editorId,
        { timeout: 60 * 1000 },
    );

    await page.evaluate(
        ({ id, value }) => {
            const editor = (window as any).tinymce.get(id);

            editor.setContent(value);
            editor.fire("keyup");
            editor.save();
        },
        { id: editorId, value: content },
    );

    await expectEditorHolds(page, iframeSelector, editorId, content);
}

async function expectEditorHolds(
    page: Page,
    iframeSelector: string,
    editorId: string,
    content: string,
): Promise<void> {
    if (!/<[a-z][^>]*>/i.test(content)) {
        await expect(
            page.frameLocator(iframeSelector).locator("body"),
        ).toHaveText(content);

        return;
    }

    const expectedText = await page.evaluate((html) => {
        const holder = document.createElement("div");

        holder.innerHTML = html;

        return holder.textContent ?? "";
    }, content);

    await expect
        .poll(async () =>
            withoutWhitespace(
                await page.evaluate(
                    (id) =>
                        (window as any).tinymce.get(id).getBody().textContent,
                    editorId,
                ),
            ),
        )
        .toBe(withoutWhitespace(expectedText));
}

function withoutWhitespace(text: string): string {
    return text.replace(/\s+/g, "");
}

export function withTinymce(page: Page): AdminPage {
    (page as AdminPage).fillInTinymce = (
        iframeSelector: string,
        content: string,
    ) => fillTinymce(page, iframeSelector, content);

    return page as AdminPage;
}

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

        await context.addInitScript(() => {
            window.localStorage.removeItem("datagrids");
        });

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

        await use(withTinymce(page));
        await context.close();
    },

    shopPage: async ({ browser }, use) => {
        const context = await browser.newContext();
        const page = await context.newPage();

        await use(withTinymce(page) as ShopPage);
        await context.close();
    },
});

export { expect };
