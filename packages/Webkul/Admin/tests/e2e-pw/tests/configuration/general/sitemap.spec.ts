import { test, expect } from "../../../setup";
import { SitemapConfigurationPage } from "../../../pages/admin/configuration/general/SitemapConfigurationPage";

test.describe("sitemap configuration", () => {
    test("should disable the sitemap for your website when sitemap button is disabled", async ({
        adminPage,
    }) => {
        const page = new SitemapConfigurationPage(adminPage);

        await page.setSitemapEnabled(false);
        await page.saveAndVerify();

        await page.open();
        await expect(await page.isSitemapEnabled()).toBe(false);
    });

    test("should set maximum number of urls per file", async ({
        adminPage,
    }) => {
        const page = new SitemapConfigurationPage(adminPage);

        await page.setSitemapEnabled(true);
        await page.setMaximumUrls("4000");
        await page.saveAndVerify();

        await page.open();
        await expect(await page.getMaximumUrlsValue()).toBe("4000");
    });
});
