import { test } from "../setup";
import { generateEmail } from "../utils/faker";
import { loginAsCustomer } from "../utils/customer";
import { HomePage } from "../pages/shop/HomePage";

const SUBSCRIBED = "You have successfully subscribed to our newsletter.";

const ALREADY_SUBSCRIBED = "You are already subscribed to our newsletter.";

test.describe("newsletter subscription", () => {
    let email: string;

    test.beforeEach(() => {
        email = generateEmail();
    });

    test("should allow a guest to subscribe to the newsletter", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(SUBSCRIBED);
    });

    test("should tell a guest they are already subscribed when they subscribe twice", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(SUBSCRIBED);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(ALREADY_SUBSCRIBED);
    });

    test("should allow a signed in customer to subscribe to the newsletter", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await loginAsCustomer(shopPage);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(SUBSCRIBED);
    });

    test("should tell a signed in customer they are already subscribed when they subscribe twice", async ({
        shopPage,
    }) => {
        const homePage = new HomePage(shopPage);

        await loginAsCustomer(shopPage);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(SUBSCRIBED);

        await homePage.subscribeToNewsletter(email);
        await homePage.expectSubscriptionMessage(ALREADY_SUBSCRIBED);
    });
});
