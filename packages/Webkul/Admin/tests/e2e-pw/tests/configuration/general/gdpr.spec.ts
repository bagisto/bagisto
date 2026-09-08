import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    GDPRConfigurationPage,
    type GdprSettings,
} from "../../../pages/admin/configuration/general/GDPRConfigurationPage";
import {
    GdprShopPage,
    type CookieNoticePosition,
} from "../../../pages/shop/GdprShopPage";
import { loginAsCustomer } from "../../../utils/customer";

test.describe("gdpr configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let gdprPage: GDPRConfigurationPage;
    let original: GdprSettings;

    test.beforeEach(async ({ adminPage }) => {
        gdprPage = new GDPRConfigurationPage(adminPage);
        original = await gdprPage.readSettings();
    });

    test.afterEach(async () => {
        await gdprPage.applySettings(original);
    });

    test("should offer gdpr requests to signed in customers only while gdpr is enabled", async ({
        shopPage,
    }) => {
        const shop = new GdprShopPage(shopPage);

        await loginAsCustomer(shopPage);

        await gdprPage.applySettings({ enabled: true });
        await shop.expectGdprRequestsOffered(true);

        await gdprPage.applySettings({ enabled: false });
        await shop.expectGdprRequestsOffered(false);
    });

    test("should require the customer agreement on registration only while it is enabled", async ({
        shopPage,
    }) => {
        const shop = new GdprShopPage(shopPage);
        const label = `I agree with this statement ${uniqueStamp()}`;

        await gdprPage.applySettings({
            enabled: true,
            agreementEnabled: true,
            agreementLabel: label,
        });
        await shop.expectRegistrationAgreement(label);

        await gdprPage.applySettings({ agreementEnabled: false });
        await shop.expectNoRegistrationAgreement();
    });

    for (const position of ["bottom-left", "bottom-right"] as CookieNoticePosition[]) {
        test(`should place the cookie notice at the ${position}`, async ({
            shopPage,
        }) => {
            const shop = new GdprShopPage(shopPage);
            const description = `This website uses cookies ${uniqueStamp()}`;

            await gdprPage.applySettings({
                enabled: true,
                cookieEnabled: true,
                cookiePosition: position,
                cookieBlockIdentifier: "cookie block",
                cookieDescription: description,
            });

            await shop.expectCookieNoticeAt(position, description);
        });
    }

    test("should hide the cookie notice once a visitor accepts it", async ({
        shopPage,
    }) => {
        const shop = new GdprShopPage(shopPage);
        const description = `This website uses cookies ${uniqueStamp()}`;

        await gdprPage.applySettings({
            enabled: true,
            cookieEnabled: true,
            cookiePosition: "bottom-left",
            cookieBlockIdentifier: "cookie block",
            cookieDescription: description,
        });

        await shop.expectCookieNoticeAt("bottom-left", description);
        await shop.acceptCookies();
        await shop.expectCookieNoticeHidden();
    });

    test("should store the consent preference a signed in customer saves as a cookie", async ({
        shopPage,
    }) => {
        const shop = new GdprShopPage(shopPage);
        const description = `This website uses cookies ${uniqueStamp()}`;

        await loginAsCustomer(shopPage);

        await gdprPage.applySettings({
            enabled: true,
            cookieEnabled: true,
            cookiePosition: "bottom-left",
            cookieBlockIdentifier: "cookie block",
            cookieDescription: description,
        });

        await shop.expectCookieNoticeAt("bottom-left", description);
        await shop.saveCookieConsent();

        await shop.expectPreferenceCookie("basic_interaction", "true");
    });

    test("should hide the cookie notice once a signed in customer saves their consent preferences", async ({
        shopPage,
    }) => {
        const shop = new GdprShopPage(shopPage);
        const description = `This website uses cookies ${uniqueStamp()}`;

        await loginAsCustomer(shopPage);

        await gdprPage.applySettings({
            enabled: true,
            cookieEnabled: true,
            cookiePosition: "bottom-left",
            cookieBlockIdentifier: "cookie block",
            cookieDescription: description,
        });

        await shop.expectCookieNoticeAt("bottom-left", description);
        await shop.saveCookieConsent();
        await shop.expectCookieNoticeHidden();
    });
});
