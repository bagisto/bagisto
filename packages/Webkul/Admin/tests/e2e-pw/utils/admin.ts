import type { Page } from "@playwright/test";
import { env } from "./env";

export async function loginAsAdmin(page: Page) {
    const adminCredentials = {
        email: env.adminEmail,
        password: env.adminPassword,
    };

    await page.goto("admin/login");
    await page.fill('input[name="email"]', adminCredentials.email);
    await page.fill('input[name="password"]', adminCredentials.password);
    await page.press('input[name="password"]', "Enter");

    await page.waitForURL("**/admin/dashboard");

    return adminCredentials;
}
