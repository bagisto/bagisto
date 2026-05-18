import { test, expect } from "../../../setup";
import { CustomerSettingsPage } from "../../../pages/admin/configuration/customer/CustomerSettingsPage";

test.describe("settings configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new CustomerSettingsPage(adminPage).open();
    });

    test("should enable the wishlist feature", async ({ adminPage }) => {
        await new CustomerSettingsPage(adminPage).enableWishlist();
    });

    test("should update the redirect page option after the login", async ({
        adminPage,
    }) => {
        await new CustomerSettingsPage(adminPage).updateLoginRedirect("home");
    });

    test("should update default customer group and enabling the newsletter subscription option during sign-up", async ({
        adminPage,
    }) => {
        await new CustomerSettingsPage(
            adminPage,
        ).updateDefaultGroupAndNewsletter();
    });

    test("should update the newsletter subscription option", async ({
        adminPage,
    }) => {
        await new CustomerSettingsPage(
            adminPage,
        ).enableNewsletterSubscription();
    });

    test.describe("Social login configuration", () => {
        test("should enable the Github login ", async ({ adminPage }) => {
            await new CustomerSettingsPage(adminPage).enableSocialLogin(
                "github",
            );
        });

        test("should enable the linkedin login ", async ({ adminPage }) => {
            await new CustomerSettingsPage(adminPage).enableSocialLogin(
                "linkedin",
            );
        });

        test("should enable the google login ", async ({ adminPage }) => {
            await new CustomerSettingsPage(adminPage).enableSocialLogin(
                "google",
            );
        });

        test("should enable the twitter login ", async ({ adminPage }) => {
            await new CustomerSettingsPage(adminPage).enableSocialLogin(
                "twitter",
            );
        });

        test("should enable the facebook login ", async ({ adminPage }) => {
            await new CustomerSettingsPage(adminPage).enableSocialLogin(
                "facebook",
            );
        });
    });
});
