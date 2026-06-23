import { test, expect } from "../../setup";
import type { Page } from "@playwright/test";
import { generateHostname } from "../../utils/faker";

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

async function confirmAgreeDialog(adminPage: Page) {
    await adminPage
        .getByRole("button", { name: "Agree", exact: true })
        .click();
}

async function massDeleteSelectedRows(adminPage: Page) {
    await adminPage.locator(".icon-uncheckbox").first().click();
    await adminPage
        .getByRole("button", { name: "Select Action " })
        .click();
    await adminPage.getByRole("link", { name: "Delete" }).click();
    await confirmAgreeDialog(adminPage);
}


test.describe("search-seo management", () => {
    test.describe("url rewrites management", () => {
        test("should create seo search url rewrite for temporary redirect type", async ({
            adminPage,
        }) => {
            /**
             * SEO main content will be filled and generated.
             */
            const seo = {
                url: generateHostname(),
                product: "product",
            };

            /**
             * Reaching to the url rewrite page.
             */
            await openSeoSection(adminPage, URL_REWRITES_URL);

            /**
             * Opening create url rewrite form in modal.
             */
            await adminPage.getByText("Create URL Rewrite").click();

            /**
             * Filling the modal form for url rewrite for temporary redirect type.
             */
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

            /**
             * Saving the temporary redirect type url rewrite.
             */
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite created successfully")
            ).toBeVisible();
        });

        test("should create seo search url rewrite for permanent redirect type", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the url rewrite page.
             */
            const seo = {
                url: generateHostname(),
                product: "product",
            };

            await openSeoSection(adminPage, URL_REWRITES_URL);

            /**
             * Opening create rewrite URL form in modal.
             */
            await adminPage.getByText("Create URL Rewrite").click();

            /**
             * Filling the modal form for url rewrite for permanent redirect type.
             */
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

            /**
             * Saving the permanent redirect type in url rewrite.
             */
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite created successfully")
            ).toBeVisible();
        });

        test("should edit the url redirect for requested path", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };

            /**
             * Updating the url for requested path.
             */
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            /**
             * Editing the requested path url.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Request Path" });
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .press("ArrowRight");
            await adminPage
                .getByRole("textbox", { name: "Request Path" })
                .fill(seo.url);

            /**
             * Saving the requested path url.
             */
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite updated successfully")
            ).toBeVisible();
        });

        test("should edit the url redirect for target path", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };

            /**
             * Updating the url for targeted path.
             */
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            /**
             * Editing the targeted url rewrite.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Target Path" });
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .press("ArrowRight");
            await adminPage
                .getByRole("textbox", { name: "Target Path" })
                .fill(seo.url);

            /**
             * Saving the targeted path.
             */
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            await expect(
                adminPage.getByText("URL Rewrite updated successfully")
            ).toBeVisible();
        });

        test("should edit redirect type permanent to temporary", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            /**
             * Editing the redirect type permanent to temporary.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("302");
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            /**
             * Saving the redirect type after updating.
             */
            await adminPage.getByRole("button", { name: "Save URL Rewrite" });

            await expect(
                adminPage.getByText("URL Rewrite updated successfully")
            ).toBeVisible();
        });

        test("should edit redirect type temporary to permanent", async ({
            adminPage,
        }) => {
            await openSeoSection(adminPage, URL_REWRITES_URL);
            await adminPage.getByRole("link", { name: "URL Rewrites" }).click();

            /**
             * Editing the redirect type temporary to permanent.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="redirect_type"]')
                .selectOption("301");
            await adminPage
                .getByRole("button", { name: "Save URL Rewrite" })
                .click();

            /**
             * Saving the redirect type after updating.
             */
            await adminPage.getByRole("button", { name: "Save URL Rewrite" });

            await expect(
                adminPage.getByText("URL Rewrite updated successfully")
            ).toBeVisible();
        });

        test("should delete url redirect via delete button", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the url rewrite page.
             */
            await openSeoSection(adminPage, URL_REWRITES_URL);

            /**
             * Clicking delete icon for individual deleting the url rewrite.
             */
            await adminPage
                .locator(".row > .flex > a:nth-child(2)")
                .first()
                .click();
            await confirmAgreeDialog(adminPage);

            await expect(
                adminPage.getByText("URL Rewrite deleted")
            ).toBeVisible();
        });

        test("should delete url redirect via mass delete", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the url rewrite page.
             */
            await openSeoSection(adminPage, URL_REWRITES_URL);

            /**
             * Selecting all list with mass delete checkbox.
             */
            await massDeleteSelectedRows(adminPage);

            await expect(
                adminPage.getByText("Selected URL Rewrites Deleted")
            ).toBeVisible();
        });
    });

    test.describe("search terms management", () => {
        test("should create new search term", async ({ adminPage }) => {
            const seo = {
                url: generateHostname(),
            };

            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Opening create search term form in modal.
             */
            await adminPage.getByText("Create Search Term").click();

            /**
             * Filling the modal form new search term.
             */
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

            /**
             * Saving the search term.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term created")
            ).toBeVisible();
        });

        test("should update search query by editing search term", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Updating the search query by editing the search term.
             */
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

            /**
             * Saving the search query.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated")
            ).toBeVisible();
        });

        test("should update results field by editing search term", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Updating the results field by editing the search term.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Results" }).click();
            await adminPage
                .getByRole("textbox", { name: "Results" })
                .fill("10");

            /**
             * Saving the updated results field.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated")
            ).toBeVisible();
        });

        test("should update uses field by editing search term", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Updating the uses field by editing the search term.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Uses" }).click();
            await adminPage.getByRole("textbox", { name: "Uses" }).fill("5");

            /**
             * Saving the updated uses field.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            await expect(
                adminPage.getByText("Search Term Updated")
            ).toBeVisible();
        });

        test("should update redirect url field by editing search term", async ({
            adminPage,
        }) => {
            const seo = {
                url: generateHostname(),
            };

            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Updating the redirect url field by editing the search term.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage
                .getByRole("textbox", { name: "Redirect Url" })
                .fill(seo.url);
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            /**
             * Saving the search query.
             */
            await expect(
                adminPage.getByText("Search Term Updated")
            ).toBeVisible();
        });

        test("should update channel by editing search term", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Updating the channel by editing the search term.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('select[name="channel_id"]')
                .selectOption("1");
            await adminPage
                .getByRole("button", { name: "Save Search Term" })
                .click();

            /**
             * Saving the updated channel.
             */
            await expect(
                adminPage.getByText("Search Term Updated")
            ).toBeVisible();
        });

        test("should delete selected search term", async ({ adminPage }) => {
            /**
             * Reaching to the search term page.
             */
            await openSeoSection(adminPage, SEARCH_TERMS_URL);

            /**
             * Selecting the search term for deleting.
             */
            await adminPage
                .locator("div:nth-child(1) > p > label > .icon-uncheckbox")
                .click();

            /**
             * Select action to delete the selected search terms.
             */
            await adminPage
                .getByRole("button", { name: "Select Action " })
                .click();
            await adminPage.getByRole("link", { name: "Delete" }).click();

            /**
             * Select warning message box for confirmation to delete selected search terms.
             */
            await confirmAgreeDialog(adminPage);

            /**
             * Saving the updated locale.
             */
            await expect(
                adminPage.getByText(
                    "Selected Search Terms Deleted Successfully"
                )
            ).toBeVisible();
        });
    });

    test.describe("search synonyms management", () => {
        test("should create new search synonym", async ({ adminPage }) => {
            /**
             * Reaching to the search synonym page.
             */
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            /**
             * Opening create search synonym form in modal.
             */
            await adminPage.getByText("Create Search Synonym").click();

            /**
             * Filling the modal form for new search synonym.
             */
            await adminPage.getByRole("textbox", { name: "Name" }).click();
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .fill("Bottom Wear");
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .fill(
                    "Jeans,Lowers,Shorts,Running Shorts,Sports Leggings,Trousers"
                );

            /**
             * Saving the search synonym.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym created")
            ).toBeVisible();
        });

        test("should update name in search synonym", async ({ adminPage }) => {
            /**
             * Reaching to the search synonym page.
             */
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            /**
             * Updating the name by editing the search synonym.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Name" }).click();
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .fill("Top Wear");

            /**
             * Saving the search synonym.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym updated successfully")
            ).toBeVisible();
        });

        test("should update terms in search synonym", async ({ adminPage }) => {
            /**
             * Reaching to the search synonym page.
             */
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            /**
             * Updating the terms by editing the search synonym.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Terms" }).click();
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Terms" })
                .fill(
                    "topwear, tops, upper wear, shirts, t-shirts, blouses, tank tops, tunics, sweatshirts, hoodies, jackets, coats"
                );

            /**
             * Saving the search synonym.
             */
            await adminPage
                .getByRole("button", { name: "Save Search Synonym" })
                .click();

            await expect(
                adminPage.getByText("Search Synonym updated successfully")
            ).toBeVisible();
        });

        test("should delete search synonyms with mass delete", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the search synonym page.
             */
            await openSeoSection(adminPage, SEARCH_SYNONYMS_URL);

            /**
             * Selecting all list with mass delete checkbox.
             */

            await massDeleteSelectedRows(adminPage);

            await expect(
                adminPage.getByText(
                    "Selected Search Synonyms Deleted Successfully"
                )
            ).toBeVisible();
        });
    });

    test.describe("sitemaps management", () => {
        test("should create new sitemap", async ({ adminPage }) => {
            /**
             * Reaching to the sitemap page.
             */
            await openSeoSection(adminPage, SITEMAPS_URL);

            /**
             * Opening create sitemap form in modal.
             */
            await adminPage.getByText("Create Sitemap").click();

            /**
             * Filling the modal form new sitemap.
             */
            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap1.xml");
            await adminPage.locator('input[name="path"]').fill("/sitemap/");

            /**
             * Saving the sitemap.
             */
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap created successfully")
            ).toBeVisible();
        });

        test("should update file name in sitemap", async ({ adminPage }) => {
            /**
             * Reaching to the sitemap page.
             */
            await openSeoSection(adminPage, SITEMAPS_URL);

            /**
             * Updating the file name by editing the sitemaps.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap1.xml");
            await adminPage
                .locator('input[name="file_name"]')
                .fill("sitemap2.xml");

            /**
             * Saving the updated sitemap.
             */
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();

            await expect(
                adminPage.getByText("Sitemap Updated successfully")
            ).toBeVisible();
        });

        test("should update path in sitemap", async ({ adminPage }) => {
            /**
             * Reaching to the sitemap page.
             */
            await openSeoSection(adminPage, SITEMAPS_URL);

            /**
             * Updating the file name by editing the sitemaps.
             */
            await openFirstRecordForEdit(adminPage);
            await adminPage.getByRole("textbox", { name: "Path" }).click();
            await adminPage
                .getByRole("textbox", { name: "Path" })
                .press("ControlOrMeta+a");
            await adminPage
                .getByRole("textbox", { name: "Path" })
                .fill("/new_path/");

            /**
             * Saving the updated sitemap.
             */
            await adminPage
                .getByRole("button", { name: "Save Sitemap" })
                .click();
            await expect(
                adminPage.getByText("Sitemap Updated successfully")
            ).toBeVisible();
        });

        test("should delete sitemap via delete button", async ({
            adminPage,
        }) => {
            /**
             * Reaching to the sitemap page.
             */
            await openSeoSection(adminPage, SITEMAPS_URL);

            /**
             * Clicking on the delete button.
             */
            await adminPage
                .locator(".row > .flex > a:nth-child(2)")
                .first()
                .click();

            /**
             * Select warning message box for confirmation to delete selected sitemap.
             */
            await confirmAgreeDialog(adminPage);
            await expect(
                adminPage.getByText("Sitemap Deleted successfully")
            ).toBeVisible();
        });
    });
});
