import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import { HomePage } from "../pages/shop/HomePage";

async function subscribeToNewsletter(
    homePage: HomePage,
    email: string,
    expectedMessage: string,
) {
    await homePage.subscribeToNewsletter(email);
    await homePage.expectSubscriptionMessage(expectedMessage);
}

test.describe("Newsletter subscription", () => {
    test("should allow guest to subscribe to newsletter successfully", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await subscribeToNewsletter(
            homePage,
            "guest@example.com",
            "You have successfully subscribed to our newsletter.",
        );
    });

    test("should not allow guest to subscribe to newsletter again", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await subscribeToNewsletter(
            homePage,
            "guest@example.com",
            "You are already subscribed to our newsletter.",
        );
    });

    test("should allow customer to subscribe to newsletter", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await loginAsCustomer(shopPage);
        await subscribeToNewsletter(
            homePage,
            "customer1@example.com",
            "You have successfully subscribed to our newsletter.",
        );
    });

    test("should not allow customer to subscribe to newsletter again", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        await shopPage.goto("admin/customers");
        await shopPage
            .locator('input[name="email"]')
            .fill(adminCredentials.email);
        await shopPage
            .locator('input[name="password"]')
            .fill(adminCredentials.password);
        await shopPage.press('input[name="password"]', "Enter");

        await shopPage.locator(".icon-login").first().click();
        await shopPage.waitForLoadState("networkidle");
        await homePage.gotoHome();

        await subscribeToNewsletter(
            homePage,
            "customer1@example.com",
            "You are already subscribed to our newsletter.",
        );
    });
});
