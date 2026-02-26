export async function loginAsAdmin(page) {
    /**
     * Admin credentials.
     */
    const adminCredentials = {
        email: "admin@example.com",
        password: "admin123",
    };

    /**
     * Authenticate the admin user.
     */
    await page.goto("admin/login");
    await page.locator('input[name="email"]').fill(adminCredentials.email);
    await page.locator('input[name="password"]').fill(adminCredentials.password);
    await page.press('input[name="password"]', "Enter");

    /**
     * Wait for the dashboard to load.
     */
    await page.waitForURL("**/admin/dashboard");

    return adminCredentials;
}