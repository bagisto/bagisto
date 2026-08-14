import { test, expect } from "../../setup";
import type { Page } from "@playwright/test";
import {
    generateHostname,
    generateRandomNumericString,
} from "../../utils/faker";
import { ChannelsPage } from "../../pages/admin/settings/ChannelsPage";

const URL_REWRITES_URL = "admin/marketing/search-seo/url-rewrites";
const SEARCH_TERMS_URL = "admin/marketing/search-seo/search-terms";
const SEARCH_SYNONYMS_URL = "admin/marketing/search-seo/search-synonyms";
const SITEMAPS_URL = "admin/marketing/search-seo/sitemaps";

async function openSeoSection(adminPage: Page, url: string) {
    await adminPage.goto(url);
}

async function openFirstRecordForEdit(adminPage: Page) {
    await adminPage.locator(".row > .flex > a").first().click();
}

// The sitemap listing has an extra "Link for Google" URL column whose <a> is
// also a direct `.row > .flex > a`, so the generic helper above would click the
// (target="_blank") sitemap link instead of the edit action. Scope to the
// actions column (`.justify-end`) to reliably open the edit modal.
async function openFirstSitemapForEdit(adminPage: Page) {
    await adminPage.locator(".row .justify-end > a").first().click();
}

async function confirmAgreeDialog(adminPage: Page) {
    await adminPage.getByRole("button", { name: "Agree", exact: true }).click();
}

async function selectSitemapChannel(adminPage: Page, channelName: string) {
    const label = adminPage
        .locator('label[for^="channels_"]')
        .filter({ hasText: channelName })
        .first();

    const forId = await label.getAttribute("for");

    await label.click();

    // Note: the custom checkbox renders both an <input> and a <label> sharing
    // the same id, so the assertion must be scoped to the input element.
    await expect(adminPage.locator(`input#${forId}`)).toBeChecked();
}

async function massDeleteSelectedRows(adminPage: Page) {
    await adminPage.locator(".icon-uncheckbox").first().click();
    await adminPage.getByRole("button", { name: "Select Action " }).click();
    await adminPage.getByRole("link", { name: "Delete" }).click();
    await confirmAgreeDialog(adminPage);
}

test.describe("search-seo management", () => {
    test.describe("url rewrites management", () => {
        test("should create seo search url rewrite for temporary redirect type", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
                product: "product",
            };

            await openSeoSection(adminPage, URL_REWRITES_URL);

            await adminPage.getByText("Create URL Rewrite").click();

            await adminPage
                .locator('select[name="entity_type"]')
                .selectOption(seo.product);
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .click();
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .fill(seo.url);
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .click();
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .fill(seo.url);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("301");
            await adminPage.locator('select[name="locale"]').selectOption("en");

            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite created successfully"),
            ).toBeVisible();
        });

        test("should create seo search url rewrite for permanent redirect type", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
                product: "product",
            };

            await openSeoSection(adminPage, URL_REWRITES_URL);

            await adminPage.getByText("Create URL Rewrite").click();

            await adminPage
                .locator('select[name="entity_type"]')
                .selectOption(seo.product);
            await adminPage.getByRole("textbox", { name: "Request Path" });
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .fill(seo.url);
            await adminPage.getByRole("textbox", { name: "Target Path" });
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .fill(seo.url);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("301");
            await adminPage.locator('select[name="locale"]').selectOption("en");

            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite created successfully"),
            ).toBeVisible();
        });

        test("should edit the url redirect for requested path", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Request Path" });
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .press("ArrowRight");
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .fill(seo.url);

            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite updated successfully"),
            ).toBeVisible();
        });

        test("should edit the url redirect for target path", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };

            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Target Path" });
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .press("ArrowRight");
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .fill(seo.url);

            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite updated successfully"),
            ).toBeVisible();
        });

        test("should edit redirect type permanent to temporary", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("302");
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await adminPage.getByRole("button", { name: "Save URL Rewrite" });

            await expect(
                adminPage.getByText("URL Rewrite updated successfully"),
            ).toBeVisible();
        });

        test("should edit redirect type temporary to permanent", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("301");
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await adminPage.getByRole("button", { name: "Save URL Rewrite" });

            await expect(
                adminPage.getByText("URL Rewrite updated successfully"),
            ).toBeVisible();
        });

        test("should delete url redirect via delete button", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);

            await adminPage
                .locator(".row > .flex > a:nth-child(2)")
                .first()
                .click();
            await confirmAgreeDialog(adminPage);

            await expect(
                adminPage.getByText("URL Rewrite deleted"),
            ).toBeVisible();
        });

        test("should delete url redirect via mass delete", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);

            await massDeleteSelectedRows(adminPage);

            await expect(
                adminPage.getByText("Selected URL Rewrites Deleted"),
            ).toBeVisible();
        });
    });

    test.describe("search terms management", () => {
        test("should create new search term", async ({ adminPage }) => {
            const seo = {
                url: generateHostname(),
            };

            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await adminPage.getByText("Create Search Term").click();

            await adminPage
                .getByRole("textbox", { name: "Search Query" })
                .click();
            await adminPage
                .getByRole("textbox", { name: "Search Query" })
                .fill("Running Shoes");
            await adminPage
                .getByRole("textbox", { name: "Redirect Url" })
                .fill(seo.url);
            await adminPage
                .locator('select[name="channel_id"]')
                .selectOption("1");
            await adminPage.locator('select[name="locale"]').selectOption("en");

            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term created"),
            ).toBeVisible();
        });

        test("should update search query by editing search term", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage
                .getByRole("textbox", { name: "Search Query" })
                .click();
            await adminPage
                .getByRole("textbox", { name: "Search Query" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Search Query" })
                .fill("Boots");

            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated"),
            ).toBeVisible();
        });

        test("should update results field by editing search term", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Results" }).click();
            await adminPage
                .getByRole("textbox", { name: "Results" })
                .fill("10");

            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated"),
            ).toBeVisible();
        });

        test("should update uses field by editing search term", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SEARCH_TERMS_URL);
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Uses" }).click();
            await adminPage.getByRole("textbox", { name: "Uses" }).fill("5");

            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated"),
            ).toBeVisible();
        });

        test("should update redirect url field by editing search term", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };

            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage
                .getByRole("textbox", { name: "Redirect Url" })
                .fill(seo.url);
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated"),
            ).toBeVisible();
        });

        test("should update channel by editing search term", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="channel_id"]')
                .selectOption("1");
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated"),
            ).toBeVisible();
        });

        test("should delete selected search term", async ({ adminPage }) => {
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            await adminPage
                .locator("div:nth-child(1) > p > label > .icon-uncheckbox")
                .click();

            await adminPage
                .getByRole("button", { name: "Select Action " })
                .click();
            await adminPage.getByRole("link", { name: "Delete" }).click();

            await confirmAgreeDialog(adminPage);

            await expect(
                adminPage.getByText(
                    "Selected Search Terms Deleted Successfully",
                ),
            ).toBeVisible();
        });
    });

    test.describe("search synonyms management", () => {
        test("should create new search synonym", async ({ adminPage }) => {
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            await adminPage.getByText("Create Search Synonym").click();

            await adminPage.getByRole("textbox", { name: "Name" }).click();
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .fill("Bottom Wear");
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .fill(
                    "Jeans,Lowers,Shorts,Running Shorts,Sports Leggings,Trousers",
                );

            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym created"),
            ).toBeVisible();
        });

        test("should update name in search synonym", async ({ adminPage }) => {
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Name" }).click();
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .fill("Top Wear");

            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym updated successfully"),
            ).toBeVisible();
        });

        test("should update terms in search synonym", async ({ adminPage }) => {
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Terms" }).click();
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .fill(
                    "topwear, tops, upper wear, shirts, t-shirts, blouses, tank tops, tunics, sweatshirts, hoodies, jackets, coats",
                );

            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym updated successfully"),
            ).toBeVisible();
        });

        test("should delete search synonyms with mass delete", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            await massDeleteSelectedRows(adminPage);

            await expect(
                adminPage.getByText(
                    "Selected Search Synonyms Deleted Successfully",
                ),
            ).toBeVisible();
        });
    });

    test.describe("sitemaps management", () => {
        test("should fail to create sitemap when no channel is selected", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage.getByText("Create Sitemap").click();

            await adminPage
                .locator('input[name="file_name"]')
                .fill("no-channel.xml");
            await adminPage.locator('input[name="path"]').fill("/sitemap/");

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();

            await expect(
                adminPage.getByText("The Channels field is required"),
            ).toBeVisible();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeHidden();
        });

        test("should create new sitemap with a channel", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage.getByText("Create Sitemap").click();
            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap1.xml");
            await adminPage.locator('input[name="path"]').fill("/sitemap/");
            await adminPage.locator('label[for="channels_1"]').first().click();

            await expect(
                adminPage.locator("input#channels_1").first(),
            ).toBeChecked();

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();
        });

        test("should show the selected channel in the sitemap listing", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await expect(
                adminPage.getByText("default", { exact: true }).first(),
            ).toBeVisible();
        });

        test("should preselect the channel when editing an existing sitemap", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await openFirstSitemapForEdit(adminPage);

            await expect(
                adminPage.locator("input#channels_1").first(),
            ).toBeChecked();
        });

        test("should update file name in sitemap", async ({ adminPage }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await openFirstSitemapForEdit(adminPage);

            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap1.xml");
            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap2.xml");

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();

            await expect(
                adminPage.getByText("Sitemap Updated successfully"),
            ).toBeVisible();
        });

        test("should update path in sitemap", async ({ adminPage }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await openFirstSitemapForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Path" }).click();
            await adminPage
                .getByRole("textbox", { name: "Path" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Path" })
                .fill("/new_path/");

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap Updated successfully"),
            ).toBeVisible();
        });

        test("should fail to update sitemap when all channels are unselected", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await openFirstSitemapForEdit(adminPage);

            await expect(
                adminPage.locator("input#channels_1").first(),
            ).toBeChecked();

            await adminPage.locator('label[for="channels_1"]').first().click();
            await expect(
                adminPage.locator("input#channels_1").first(),
            ).not.toBeChecked();

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();

            await expect(
                adminPage.getByText("The Channels field is required"),
            ).toBeVisible();
            await expect(
                adminPage.getByText("Sitemap Updated successfully"),
            ).toBeHidden();
        });

        test("should create separate sitemaps for the default and a newly created channel", async ({
            adminPage,
        }) => {
            const channelsPage = new ChannelsPage(adminPage);

            const { name: newChannelName } = await channelsPage.createChannel();

            const token = generateRandomNumericString(6);
            const defaultFile = `default-${token}.xml`;
            const newChannelFile = `newchan-${token}.xml`;

            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage.getByText("Create Sitemap").click();
            await adminPage
                .locator('input[name="file_name"]')
                .fill(defaultFile);
            await adminPage
                .locator('input[name="path"]')
                .fill(`/default-${token}/`);
            await selectSitemapChannel(adminPage, "Default");
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();

            // Wait for the create modal to finish its leave transition before
            // reopening. Its title is also "Create Sitemap" (v-if-removed only
            // after the animation), so clicking too early makes
            // getByText("Create Sitemap") match both the title and the button.
            await expect(
                adminPage.getByRole("button", { name: "Save Sitemap" }),
            ).toBeHidden();

            await adminPage.getByText("Create Sitemap").click();

            await expect(
                adminPage.locator('input[name="file_name"]'),
            ).toHaveValue("");

            await adminPage
                .locator('input[name="file_name"]')
                .fill(newChannelFile);
            await adminPage
                .locator('input[name="path"]')
                .fill(`/newchan-${token}/`);
            await selectSitemapChannel(adminPage, newChannelName);
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();

            await expect(
                adminPage.getByText(defaultFile, { exact: true }),
            ).toBeVisible();
            await expect(
                adminPage.getByText(newChannelFile, { exact: true }),
            ).toBeVisible();
        });

        test("should create a new sitemap for a different channel instead of updating the existing one", async ({
            adminPage,
        }) => {
            const channelsPage = new ChannelsPage(adminPage);

            const { name: newChannelName } = await channelsPage.createChannel();

            const token = generateRandomNumericString(6);
            const existingFile = `existing-${token}.xml`;
            const createdFile = `created-${token}.xml`;

            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage.getByText("Create Sitemap").click();
            await adminPage
                .locator('input[name="file_name"]')
                .fill(existingFile);
            await adminPage
                .locator('input[name="path"]')
                .fill(`/existing-${token}/`);
            await selectSitemapChannel(adminPage, "Default");
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();

            const existingRow = adminPage
                .locator(".row")
                .filter({ hasText: existingFile })
                .first();
            await existingRow.locator(".justify-end > a").first().click();
            await expect(
                adminPage.locator('input[name="file_name"]'),
            ).toHaveValue(existingFile);
            await adminPage.locator(".icon-cancel-1").first().click();

            await adminPage.getByText("Create Sitemap").click();

            await expect(
                adminPage.locator('input[name="file_name"]'),
            ).toHaveValue("");

            await adminPage
                .locator('input[name="file_name"]')
                .fill(createdFile);
            await adminPage
                .locator('input[name="path"]')
                .fill(`/created-${token}/`);
            await selectSitemapChannel(adminPage, newChannelName);

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();

            await expect(
                adminPage.getByText(existingFile, { exact: true }),
            ).toBeVisible();
            await expect(
                adminPage.getByText(createdFile, { exact: true }),
            ).toBeVisible();
        });

        test("should open the generated sitemap when clicking its link", async ({
            adminPage,
        }) => {
            const token = generateRandomNumericString(6);
            const generatedFile = `generated-${token}.xml`;

            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage.getByText("Create Sitemap").click();

            await adminPage
                .locator('input[name="file_name"]')
                .fill(generatedFile);
            await adminPage
                .locator('input[name="path"]')
                .fill(`/generated-${token}/`);
            await adminPage.locator('label[for="channels_1"]').first().click();

            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();

            await expect(
                adminPage.getByText("Sitemap created successfully"),
            ).toBeVisible();

            const generatedRow = adminPage
                .locator(".row")
                .filter({ hasText: generatedFile })
                .first();

            const sitemapLink = generatedRow.locator('a[target="_blank"]');

            await expect(sitemapLink).toBeVisible();

            const [sitemapPage] = await Promise.all([
                adminPage.context().waitForEvent("page"),
                sitemapLink.click(),
            ]);

            await sitemapPage.waitForLoadState();

            await expect(sitemapPage).toHaveURL(
                /storage\/sitemaps\/.*generated-.*\.xml$/,
            );

            const content = await sitemapPage.locator("body").textContent();

            expect(content).toMatch(/<urlset|<sitemapindex/);
        });

        test("should delete sitemap via delete button", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, SITEMAPS_URL);

            await adminPage
                .locator(".row > .flex > a:nth-child(2)")
                .first()
                .click();

            await confirmAgreeDialog(adminPage);

            await expect(
                adminPage.getByText("Sitemap Deleted successfully"),
            ).toBeVisible();
        });
    });
});
