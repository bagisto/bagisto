import { test } from "../../../setup";
import {
    SitemapConfigurationPage,
    type SitemapSettings,
} from "../../../pages/admin/configuration/general/SitemapConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

test.describe("sitemap configuration", () => {
    test.describe.configure({ timeout: 120000 });

    let configPage: SitemapConfigurationPage;
    let original: SitemapSettings;

    test.beforeEach(async ({ adminPage }) => {
        configPage = new SitemapConfigurationPage(adminPage);
        original = await configPage.readSettings();
    });

    test.afterEach(async () => {
        await configPage.applySettings(original);
    });

    test("should persist the sitemap status after reload", async () => {
        const changed = { enabled: !original.enabled };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });

    test("should persist the maximum number of urls per file after reload", async () => {
        const changed = {
            maximumUrls: other(original.maximumUrls, "4000", "5000"),
        };

        await configPage.applySettings(changed);

        await configPage.expectSettings(changed);
    });
});
