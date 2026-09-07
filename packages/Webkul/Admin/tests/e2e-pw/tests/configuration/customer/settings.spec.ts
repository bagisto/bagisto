import { test } from "../../../setup";
import {
    CustomerSettingsPage,
    SOCIAL_LOGIN_PROVIDERS,
    type CustomerSettings,
} from "../../../pages/admin/configuration/customer/CustomerSettingsPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("customer settings configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: CustomerSettingsPage;
    let original: CustomerSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new CustomerSettingsPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the wishlist and newsletter settings after reload", async () => {
        const changed = {
            wishlist: !original.wishlist,
            newsletterSignup: !original.newsletterSignup,
            newsletterSubscription: !original.newsletterSubscription,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the login redirect and default group after reload", async () => {
        const changed = {
            loginRedirect: other(original.loginRedirect, "home", "account"),
            defaultGroup: other(original.defaultGroup, "general", "wholesale"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test.describe("social login configuration", () => {
        for (const provider of SOCIAL_LOGIN_PROVIDERS) {
            test(`should offer ${provider} sign in on the storefront only while it is enabled`, async () => {
                await configPage.applySettings({
                    socialLogin: { ...original.socialLogin, [provider]: true },
                });

                await configPage.expectSocialLoginOfferedOnStorefront(provider, true);

                await configPage.applySettings({
                    socialLogin: { ...original.socialLogin, [provider]: false },
                });

                await configPage.expectSocialLoginOfferedOnStorefront(provider, false);
            });
        }
    });
});
