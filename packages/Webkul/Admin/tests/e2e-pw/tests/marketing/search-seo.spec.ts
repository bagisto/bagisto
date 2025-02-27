import { test, expect } from "../../setup";
import {
    generateRandomUrl,
} from "../../utils/form";

test.describe("search-seo management", () => {

    /**
    * Test Cases for URL Rewrite Section.
    */
    test.describe("url-rewrites", () => {

    test("should create seo search url rewrite for temporary redirect type", async ({ adminPage }) => {

        /**
         * SEO Main Content Will Be Filled And Generated
         */
        const seo = {
            url: generateRandomUrl(),
            product: "product",
        };

        /**
         * Reaching to the URL Rewrite page
         */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
         * Opening create URL Rewrite URL form in modal.
         */
        await adminPage.getByText('Create URL Rewrite').click();

        /**
        * Filling the Modal form for URL Rewrite for Temporary Redirect Type.
        */
        await adminPage.locator('select[name="entity_type"]').selectOption(seo.product);
        await adminPage.getByRole('textbox', { name: 'Request Path' }).click();
        await adminPage.getByRole('textbox', { name: 'Request Path' }).fill(seo.url);
        await adminPage.getByRole('textbox', { name: 'Target Path' }).click();
        await adminPage.getByRole('textbox', { name: 'Target Path' }).fill(seo.url);
        await adminPage.locator('select[name="redirect_type"]').selectOption('301');
        await adminPage.locator('select[name="locale"]').selectOption('en');

        /**
        * Saving the Temporary Redirect Type URL Rewrite.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        await expect(
            adminPage.getByText("URL Rewrite created successfully")
        ).toBeVisible();

    });

        test("should create seo search url rewrite for permanent redirect type", async ({ adminPage }) => {

        /**
         * Reaching to the URL Rewrite page
         */
        const seo = {
            url: generateRandomUrl(),
            product: "product",
        };

        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
         * Opening create URL Rewrite URL form in modal.
         */
        await adminPage.getByText('Create URL Rewrite').click();

        /**
        * Filling the Modal form for URL Rewrite for Permanent Redirect Type.
        */
        await adminPage.locator('select[name="entity_type"]').selectOption(seo.product);
        await adminPage.getByRole('textbox', { name: 'Request Path' })
        await adminPage.getByRole('textbox', { name: 'Request Path' }).fill(seo.url);
        await adminPage.getByRole('textbox', { name: 'Target Path' })
        await adminPage.getByRole('textbox', { name: 'Target Path' }).fill(seo.url);
        await adminPage.locator('select[name="redirect_type"]').selectOption('301');
        await adminPage.locator('select[name="locale"]').selectOption('en');

        /**
        * Saving the Permanent Redirect Type URL Rewrite.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        await expect(
            adminPage.getByText("URL Rewrite created successfully")
        ).toBeVisible();
    });

        test("should edit the url redirect for requested path", async ({ adminPage }) => {

        const seo = {
            url: generateRandomUrl(), 
        };

        /**
        * Updating the URL for Requested Path.
        */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
        await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

        /**
        * Editing the Requested Path URL.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Request Path' })
        await adminPage.getByRole('textbox', { name: 'Request Path' }).press('ArrowRight');
        await adminPage.getByRole('textbox', { name: 'Request Path' }).fill(seo.url);


        /**
        * Saving the Requested Path URL Rewrite after updating.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();

    });

        test("should edit the url redirect for target path", async ({ adminPage }) => {

        const seo = {
            url: generateRandomUrl(), 
        };

        /**
        * Updating the URL for Targeted Path.
        */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
        await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

        /**
        * Editing the Targeted URL Rewrite.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Target Path' })
        await adminPage.getByRole('textbox', { name: 'Target Path' }).press('ArrowRight');
        await adminPage.getByRole('textbox', { name: 'Target Path' }).fill(seo.url);


        /**
        * Saving the Targeted Path URL after updating.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();
    });

        test("should edit redirect type permanent to temporary", async ({ adminPage }) => {

        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
        await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

        /**
        * Editing the Redirect Type Permanent to Temporary.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('select[name="redirect_type"]').selectOption('302');
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        /**
        * Saving the Redirect Type after updating.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' })

        await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();

    });

        test("should edit redirect type temporary to permanent", async ({ adminPage }) => {

        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
        await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

        /**
        * Editing the Redirect Type Temporary to Permanent.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('select[name="redirect_type"]').selectOption('301');
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();

        /**
        * Saving the Redirect Type after updating.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' })

        await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();

    });

        test("should edit the locale", async ({ adminPage }) => {

        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
        await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

        /**
        * Editing the Locale for the URL Redirect.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('select[name="locale"]').selectOption('ar');
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();


        /**
        * Saving the Redirect Type after updating.
        */
        await adminPage.getByRole('button', { name: 'Save URL Rewrite' })

        await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();
    });

        test("should delete url redirect with delete icon", async ({ adminPage }) => {
        /**
        * Reaching to the URL Rewrite page
        */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
        * Clicking delete icon for individual deleting the URL Rewrite.
        */
        await adminPage.locator('.row > .flex > a:nth-child(2)').first().click();
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click()

        await expect(
            adminPage.getByText("URL Rewrite deleted")
        ).toBeVisible();
    });

        test("should delete with checkbox selection", async ({ adminPage }) => {
        /**
         * Reaching to the URL Rewrite page
         */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
         * Selecting checkboxes for deleting the URL Rewrite.
         */

        await adminPage.locator('div:nth-child(1) > p > label > .icon-uncheckbox').click();
        await adminPage.locator('div:nth-child(2) > p > label > .icon-uncheckbox').click();

        /**
        * Select Action to delete the selected URL Rewrites.
        */

        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();

        /**
        * Select Warning Message box for confirmation to delete selected URL Rewrites.
        */
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        await expect(
            adminPage.getByText("Selected URL Rewrites Deleted")
        ).toBeVisible();
    });

        test("should delete with mass delete", async ({ adminPage }) => {
        
        /**
         * Reaching to the URL Rewrite page
         */
        await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
         * Selecting All List with Mass Delete Checkbox.
         */
        await adminPage.locator('.icon-uncheckbox').first().click();

        /**
        * Select Action to delete the selected URL Rewrites.
        */
        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();
        /**
        * Select Warning Message box for confirmation to delete selected URL Rewrites.
        */
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        await expect(
            adminPage.getByText("Selected URL Rewrites Deleted")
        ).toBeVisible();
    });

    /**
     * Test Cases for Search Terms.
     */
        test("should create new search term", async ({ adminPage }) => {
        const seo = {
            url: generateRandomUrl(), 
        };
              
        /**
         * Reaching to the Search Term Page
         */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Opening Create Search Term form in modal.
         */
        await adminPage.getByText('Create Search Term').click();

        /**
        * Filling the Modal form New Search Term.
        */
        await adminPage.getByRole('textbox', { name: 'Search Query' }).click();
        await adminPage.getByRole('textbox', { name: 'Search Query' }).fill('Running Shoes');
        await adminPage.getByRole('textbox', { name: 'Redirect Url' }).fill(seo.url);
        await adminPage.locator('select[name="channel_id"]').selectOption('1');
        await adminPage.locator('select[name="locale"]').selectOption('en');

        /**
        * Saving the Search Term.
        */
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        await expect(
            adminPage.getByText("Search Term created")
        ).toBeVisible();
    });

        test("should update search query by editing search term", async ({ adminPage }) => {

        /**
         * Reaching to the Search Term Page
         */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Updating the Search Query by Editing the Search Term.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Search Query' }).click();
        await adminPage.getByRole('textbox', { name: 'Search Query' }).press('ControlOrMeta+a');
        await adminPage.getByRole('textbox', { name: 'Search Query' }).fill('Boots');

        /**
        * Saving the Search Query.
        */
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();

    });

        test("should update results field by editing search term", async ({ adminPage }) => {

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
        * Updating the Results Field by Editing the Search Term.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Results' }).click();
        await adminPage.getByRole('textbox', { name: 'Results' }).fill('10');

        /**
        * Saving the Updated Results Field.
        */
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();

    });

        test("should update uses field by editing search term", async ({ adminPage }) => {

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Updating the Uses Field by Editing the Search Term.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Uses' }).click();
        await adminPage.getByRole('textbox', { name: 'Uses' }).fill('5');

        /**
        * Saving the Updated Uses Field.
        */
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();
    });

        test("should update redirect url field by editing search term", async ({ adminPage }) => {
        const seo = {
            url: generateRandomUrl(), 
        };

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Updating the Redirect URL Field by Editing the Search Term.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Redirect Url' }).fill(seo.url);
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        /**
        * Saving the Search Query.
        */
        await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();
    });

        test("should update channel by editing search term", async ({ adminPage }) => {

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Updating the Channel by Editing the Search Term.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('select[name="channel_id"]').selectOption('1');
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        /**
        * Saving the Updated Channel.
        */
        await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();

    });

        test("should update locale by editing search term", async ({ adminPage }) => {

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Updating the Locale by Editing the Search Term.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('select[name="locale"]').selectOption('ar');
        await adminPage.getByRole('button', { name: 'Save Search Term' }).click();

        /**
        * Saving the Updated Locale.
        */
         await expect(
            adminPage.getByText("Search Term Updated")
        ).toBeVisible();
    });

        test("should delete selected search term", async ({ adminPage }) => {

        /**
        * Reaching to the Search Term Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Selecting the Search Term for Deleting.
         */
        await adminPage.locator('div:nth-child(1) > p > label > .icon-uncheckbox').click();
        
        /**
        * Select Action to delete the selected Search Terms.
        */
        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();

        /**
        * Select Warning Message box for confirmation to delete selected Search Terms.
        */
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        /**
        * Saving the Updated Locale.
        */
        await expect(
            adminPage.getByText("Selected Search Terms Deleted Successfully")
        ).toBeVisible();
    });

        test("should delete search terms with mass delete", async ({ adminPage }) => {
        /**
         * Reaching to the Search Terms Page
         */
        await adminPage.goto(`admin/marketing/search-seo/search-terms`);

        /**
         * Selecting All List with Mass Delete Checkbox.
         */
        await adminPage.locator('.icon-uncheckbox').first().click();

        /**
        * Select Action to delete the selected Search Terms.
        */
        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();
        
        /**
        * Select Warning Message box for confirmation to delete selected Search Terms.
        */

        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        await expect(
            adminPage.getByText("Selected Search Terms Deleted Successfully")
        ).toBeVisible();
    });

        /**
         * Test Cases for Search Synonyms.
         */
        test("should create new search synonym", async ({ adminPage }) => {
        
        /**
         * Reaching to the Search Synonym Page
         */
        await adminPage.goto(`admin/marketing/search-seo/search-synonyms`);

        /**
         * Opening create Search Synonym form in modal.
         */
        await adminPage.getByText('Create Search Synonym').click();

        /**
        * Filling the Modal form New Search Synonym.
        */
        await adminPage.getByRole('textbox', { name: 'Name' }).click();
        await adminPage.getByRole('textbox', { name: 'Name' }).fill('Bottom Wear');
        await adminPage.getByRole('textbox', { name: 'Terms' }).fill('Jeans,Lowers,Shorts,Running Shorts,Sports Leggings,Trousers');

        /**
       * Saving the Search Synonym.
       */
        await adminPage.getByRole('button', { name: 'Save Search Synonym' }).click();

        await expect(
            adminPage.getByText("Search Synonym created")
        ).toBeVisible();
    });

        test("should update name in search synonym", async ({ adminPage }) => {

        /**
        * Reaching to the Search Synonym Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-synonyms`);

        /**
        * Updating the Name by Editing the Search Synonym.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Name' }).click();
        await adminPage.getByRole('textbox', { name: 'Name' }).press('ControlOrMeta+a');
        await adminPage.getByRole('textbox', { name: 'Name' }).fill('Top Wear');

        /**
        * Saving the Search Synonym.
        */
        await adminPage.getByRole('button', { name: 'Save Search Synonym' }).click();

        await expect(
            adminPage.getByText("Search Synonym updated successfully")
        ).toBeVisible();
    });

        test("should update terms in search synonym", async ({ adminPage }) => {

        /**
        * Reaching to the Search Synonym Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-synonyms`);

        /**
        * Updating the Terms by Editing the Search Synonym.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Terms' }).click();
        await adminPage.getByRole('textbox', { name: 'Terms' }).press('ControlOrMeta+a');
        await adminPage.getByRole('textbox', { name: 'Terms' }).fill('topwear, tops, upper wear, shirts, t-shirts, blouses, tank tops, tunics, sweatshirts, hoodies, jackets, coats');

        /**
        * Saving the Search Synonym.
        */
        await adminPage.getByRole('button', { name: 'Save Search Synonym' }).click();

        await expect(
            adminPage.getByText("Search Synonym updated successfully")
        ).toBeVisible();
    });

        test("should delete selected search synonym", async ({ adminPage }) => {

        /**
        * Reaching to the Search Synonym Page
        */
        await adminPage.goto(`admin/marketing/search-seo/search-synonyms`);

        /**
         * Selecting the Search Synonyms for Deleting.
         */
        await adminPage.locator('div:nth-child(1) > p > label > .icon-uncheckbox').click();
    
        /**
        * Select Action to delete the selected Search Synonyms.
        */
        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();

        /**
        * Select Warning Message box for confirmation to delete selected Search Terms.
        */
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        /**
        * Saving the Updated Locale.
        */
        await expect(
            adminPage.getByText("Selected Search Synonyms Deleted Successfully")
        ).toBeVisible();
    });

        test("should delete search synonyms with mass delete", async ({ adminPage }) => {
        /**
         * Reaching to the Search Terms Page
         */
        await adminPage.goto(`admin/marketing/search-seo/search-synonyms`);

        /**
         * Selecting All List with Mass Delete Checkbox.
         */

        await adminPage.locator('.icon-uncheckbox').first().click();

        /**
        * Select Action to delete the selected Search Synonyms.
        */

        await adminPage.getByRole('button', { name: 'Select Action ' }).click();
        await adminPage.getByRole('link', { name: 'Delete' }).click();
        /**
        * Select Warning Message box for confirmation to delete selected Search Synonyms.
        */

        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        await expect(
            adminPage.getByText("Selected Search Synonyms Deleted Successfully")
        ).toBeVisible();
    });

        /**
         * Test Cases for Search Sitemaps.
         */

        test("should create new sitemap", async ({ adminPage }) => {
        
        /**
        * Reaching to the Sitemap Page
        */
        await adminPage.goto(`admin/marketing/search-seo/sitemaps`);

        /**
        * Opening Create Sitemap form in modal.
        */
        await adminPage.getByText('Create Sitemap').click();

        /**
        * Filling the Modal form New Sitemap.
        */
        await adminPage.locator('input[name="file_name"]').fill('sitemap1.xml');
        await adminPage.locator('input[name="path"]').fill('/sitemap/');

        /**
        * Saving the Sitemap.
        */
        await adminPage.getByRole('button', { name: 'Save Sitemap' }).click();
        await expect(
            adminPage.getByText("Sitemap created successfully")
        ).toBeVisible();
    });

        test("should update file name in sitemap", async ({ adminPage }) => {

        /**
        * Reaching to the Sitemap Page
        */
        await adminPage.goto(`admin/marketing/search-seo/sitemaps`);

        /**
        * Updating the File Name by Editing the Sitemaps.
        */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.locator('input[name="file_name"]').fill('sitemap1.xml');
        await adminPage.locator('input[name="file_name"]').fill('sitemap2.xml');

        /**
        * Saving the Updated Sitemap.
        */

        await adminPage.getByRole('button', { name: 'Save Sitemap' }).click();

        await expect(
        adminPage.getByText("Sitemap Updated successfully")
        ).toBeVisible();
    });

        test("should update path in sitemap", async ({ adminPage }) => {

        /**
        * Reaching to the Sitemap Page
        */
        await adminPage.goto(`admin/marketing/search-seo/sitemaps`);

        /**
         * Updating the File Name by Editing the Sitemaps.
         */
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.getByRole('textbox', { name: 'Path' }).click();
        await adminPage.getByRole('textbox', { name: 'Path' }).press('ControlOrMeta+a');
        await adminPage.getByRole('textbox', { name: 'Path' }).fill('/new_path/');

        /**
        * Saving the Updated Sitemap.
        */
        await adminPage.getByRole('button', { name: 'Save Sitemap' }).click();
        await expect(
            adminPage.getByText("Sitemap Updated successfully")
        ).toBeVisible();

    });

        test("should delete sitemap with delete icon", async ({ adminPage }) => {

        /**
        * Reaching to the Sitemap Page
        */
        await adminPage.goto(`admin/marketing/search-seo/sitemaps`);

        /**
        * Selecting the Search Synonyms for Deleting.
        */
        await adminPage.locator('.row > .flex > a:nth-child(2)').first().click();

        /**
        * Select Warning Message box for confirmation to delete selected Sitemap.
        */
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();
        await expect(
        adminPage.getByText("Sitemap Deleted successfully")
        ).toBeVisible();
        });
    });
});