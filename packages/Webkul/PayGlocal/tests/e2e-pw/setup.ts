import { test as base, type Page } from "@playwright/test";
import fs from "fs";
import path from "path";

const ADMIN_AUTH_STATE_PATH = path.join(__dirname, ".auth", "admin-state.json");

type Fixtures = {
    adminPage: Page;
    shopPage: Page;
};

export const test = base.extend<Fixtures>({
    adminPage: async ({ browser }, use) => {
        const authExists = fs.existsSync(ADMIN_AUTH_STATE_PATH);

        const context = await browser.newContext(
            authExists ? { storageState: ADMIN_AUTH_STATE_PATH } : {}
        );

        const page = await context.newPage();

        if (authExists) {
            await page.goto("admin/dashboard");
        }

        if (! authExists || page.url().includes("admin/login")) {
            await loginAsAdmin(page);
            await context.storageState({ path: ADMIN_AUTH_STATE_PATH });
        }

        await use(page);

        await context.close();
    },

    shopPage: async ({ browser }, use) => {
        const context = await browser.newContext();

        const page = await context.newPage();

        await use(page);

        await context.close();
    },
});

const ADMIN_EMAIL = process.env.ADMIN_EMAIL || "admin@example.com";
const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD || "admin123";

async function loginAsAdmin(page: Page) {
    await page.goto("admin/login", { timeout: 60000 });
    await page.waitForLoadState("domcontentloaded");

    await page.locator('input[name="email"]').fill(ADMIN_EMAIL);
    await page.locator('input[name="password"]').fill(ADMIN_PASSWORD);

    /**
     * The sign in button is targeted by its accessible name. A bare `button[type="submit"]`
     * also matches controls in the Laravel debug bar when it is enabled.
     */
    await page.getByRole("button", { name: /sign in/i }).click();

    await page.waitForURL("**/admin/dashboard", { timeout: 60000 });
}

export { expect } from "@playwright/test";
