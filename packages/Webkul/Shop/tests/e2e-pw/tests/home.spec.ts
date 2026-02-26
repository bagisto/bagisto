import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";

/**
 * Common helper for newsletter subscription
 */
async function subscribeToNewsletter(
    page,
    email: string,
    expectedMessage: string
) {
    await page.goto("");

    await page.getByRole("textbox", { name: "Email" }).fill(email);

    await page.getByRole("button", { name: "Subscribe" }).click();

    await expect(
        page.getByRole("paragraph").filter({
            hasText: expectedMessage,
        })
    ).toBeVisible();
}

test.describe("Newsletter subscription", () => {
    test("should allow guest to subscribe to newsletter successfully", async ({
        page,
    }) => {
        await subscribeToNewsletter(
            page,
            "guest@example.com",
            "You have successfully subscribed to our newsletter."
        );
    });

    test("should not allow guest to subscribe to newsletter again", async ({
        page,
    }) => {
        await subscribeToNewsletter(
            page,
            "guest@example.com",
            "You are already subscribed to our newsletter."
        );
    });

    test("should allow customer to subscribe to newsletter", async ({
        page,
    }) => {
        await loginAsCustomer(page);

        await subscribeToNewsletter(
            page,
            "customer1@example.com",
            "You have successfully subscribed to our newsletter."
        );
    });

    test("should not allow customer to subscribe to newsletter again", async ({
        page,
    }) => {
        /**
         * Admin credentials.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await page.goto("admin/customers");
        await page.locator('input[name="email"]').fill(adminCredentials.email);
        await page
            .locator('input[name="password"]')
            .fill(adminCredentials.password);
        await page.press('input[name="password"]', "Enter");

        await page.locator(".icon-login").first().click();
        await page.waitForLoadState("networkidle");
        await page.goto("");
        await subscribeToNewsletter(
            page,
            "customer1@example.com",
            "You are already subscribed to our newsletter."
        );
    });
});
