import { test } from "../../../setup";
import {
    ContentConfigurationPage,
    type ContentSettings,
} from "../../../pages/admin/configuration/general/ContentConfigurationPage";
import { ChannelsPage } from "../../../pages/admin/settings/ChannelsPage";
import {
    buildCurrency,
    CurrenciesPage,
} from "../../../pages/admin/settings/CurrenciesPage";
import { uniqueStamp } from "../../../utils/faker";

const DEFAULT_CHANNEL = "Default";

test.describe("content configuration", () => {
    test.describe.configure({ timeout: 180000 });

    let configPage: ContentConfigurationPage;
    let original: ContentSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new ContentConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should show the saved header offer on the storefront once the channel has a second currency", async ({
        adminPage,
    }) => {
        const stamp = uniqueStamp();
        const changed = {
            headerOfferTitle: `Offer ${stamp}`,
            redirectionTitle: `Shop now ${stamp}`,
            redirectionLink: "http://example.com/offer",
        };
        const currency = buildCurrency();
        const currenciesPage = new CurrenciesPage(adminPage);
        const channelsPage = new ChannelsPage(adminPage);

        await currenciesPage.createCurrency(currency);

        try {
            await channelsPage.setChannelCurrency(DEFAULT_CHANNEL, currency.name, true);

            await configPage.applySettings(changed);

            await configPage.expectSettings(changed);
            await configPage.expectHeaderOfferOnStorefront(
                changed.headerOfferTitle,
                changed.redirectionTitle,
            );
        } finally {
            await channelsPage.setChannelCurrency(DEFAULT_CHANNEL, currency.name, false);
            await currenciesPage.deleteCurrenciesIfPresent([currency.name]);
        }
    });

    test("should persist the custom css and javascript after reload", async () => {
        const stamp = uniqueStamp();
        const changed = {
            customCss: `.e2e-${stamp} { display: flex; }`,
            customJs: `window.e2eStamp = ${stamp};`,
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
