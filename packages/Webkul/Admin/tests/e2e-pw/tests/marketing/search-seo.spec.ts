import { test, expect } from "../../setup";
import * as forms from "../../utils/form";
test.describe("search-seo management", () => {
    test("should create SEO Search URL Rewrite", async ({ adminPage }) => {
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

         await adminPage.locator('select[name="entity_type"]').selectOption('product');
         await adminPage.getByRole('textbox', { name: 'Request Path' })
         await adminPage.getByRole('textbox', { name: 'Request Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-1ar');
         await adminPage.getByRole('textbox', { name: 'Target Path' })
         await adminPage.getByRole('textbox', { name: 'Target Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-1');
         await adminPage.locator('select[name="redirect_type"]').selectOption('302');
         await adminPage.locator('select[name="locale"]').selectOption('ar');

         /**
         * Saving the Temporary Redirect Type URL Rewrite.
         */

         await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();
         
         await expect(
            adminPage.getByText("URL Rewrite created successfully")
        ).toBeVisible();
    });

    test("should create another SEO Search URL Rewrite", async ({ adminPage }) => {
        
        /**
         * Reaching to the URL Rewrite page
         */
            await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);

        /**
         * Opening create URL Rewrite URL form in modal.
         */
            await adminPage.getByText('Create URL Rewrite').click();

         /**
         * Filling the Modal form for URL Rewrite for Permanent Redirect Type.
         */

         await adminPage.locator('select[name="entity_type"]').selectOption('product');
         await adminPage.getByRole('textbox', { name: 'Request Path' })
         await adminPage.getByRole('textbox', { name: 'Request Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-2ar');
         await adminPage.getByRole('textbox', { name: 'Target Path' })
         await adminPage.getByRole('textbox', { name: 'Target Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-2');
         await adminPage.locator('select[name="redirect_type"]').selectOption('301');
         await adminPage.locator('select[name="locale"]').selectOption('ar');

         /**
         * Saving the Permanent Redirect Type URL Rewrite.
         */

         await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();
         
         await expect(
            adminPage.getByText("URL Rewrite created successfully")
        ).toBeVisible();
    });

    test("should edit the URL Redirect", async ({ adminPage }) => {

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
         await adminPage.getByRole('textbox', { name: 'Request Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-1ar');
    
    
         /**
         * Saving the Requested Path URL Rewrite after updating.
         */
        
         await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();
         
         await expect(
            adminPage.getByText("URL Rewrite updated successfully")
        ).toBeVisible();

    });

        test("should edit another URL Redirect", async ({ adminPage }) => {

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
            await adminPage.getByRole('textbox', { name: 'Target Path' }).fill('http://192.168.15.17/bagisto/search-seo/public/simple-product-1es');
       
       
            /**
            * Saving the Targeted Path URL after updating.
            */
           
            await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();
            
            await expect(
               adminPage.getByText("URL Rewrite updated successfully")
           ).toBeVisible();
});

        test("should edit Redirect Type", async ({ adminPage }) => {

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

    test("should edit another Redirect Type", async ({ adminPage }) => {

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

test("should edit locale", async ({ adminPage }) => {

    await adminPage.goto(`admin/marketing/search-seo/url-rewrites`);
    await adminPage.getByRole('link', { name: 'URL Rewrites' }).click();

    /**
    * Editing the Locale for the URL Redirect.
    */

    await adminPage.locator('.row > .flex > a').first().click();
    await adminPage.locator('select[name="locale"]').selectOption('es');
    await adminPage.getByRole('button', { name: 'Save URL Rewrite' }).click();


    /**
    * Saving the Redirect Type after updating.
    */

    await adminPage.getByRole('button', { name: 'Save URL Rewrite' })
    
    await expect(
    adminPage.getByText("URL Rewrite updated successfully")
).toBeVisible();  

// async function createSearchTerm(adminPage) {
//     await adminPage.goto(
//         `admin/marketing/search-seo/search-terms`
//     );

//     await adminPage.click("div.primary-button:visible");

//     adminPage.hover('select[name="channel_id"]');

//     const selects = await adminPage.$$("select.custom-select:visible");

//     for (let select of selects) {
//         const options = await select.$$eval("option", (options) => {
//             return options.map((option) => option.value);
//         });

//         if (options.length > 1) {
//             const randomIndex =
//                 Math.floor(Math.random() * (options.length - 1)) + 1;

//             await select.selectOption(options[randomIndex]);
//         } else {
//             await select.selectOption(options[0]);
//         }
//     }

//     const inputs = await adminPage.$$(
//         'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
//     );

//     for (let input of inputs) {
//         await input.fill(forms.generateRandomStringWithSpaces(200));
//     }

//     adminPage.fill(
//         'input[name="redirect_url"]:visible',
//         forms.generateRandomUrl()
//     );

//     await adminPage.click('button[class="primary-button"]:visible');

//     await expect(
//         adminPage.getByText("Search Term created successfully")
//     ).toBeVisible();
// }

// async function createSearchSynonym(adminPage) {
//     await adminPage.goto(
//         `admin/marketing/search-seo/search-synonyms`
//     );

//     await adminPage.click("div.primary-button:visible");

//     adminPage.hover('input[name="name"]');

//     const inputs = await adminPage.$$(
//         'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
//     );

//     for (let input of inputs) {
//         await input.fill(forms.generateRandomStringWithSpaces(200));
//     }

//     await adminPage.click('button[class="primary-button"]:visible');

//     await expect(
//         adminPage.getByText("Search Synonym created successfully")
//     ).toBeVisible();
// }

// test.describe("search seo management", () => {
//     test("create url rewrite", async ({ adminPage }) => {
//         await createUrlRewrite(adminPage);
//     });

//     tethest("edit url rewrite", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/url-rewrites`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconEdit = await adminPage.$$(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconEdit[0].click();

//         adminPage.hover('select[name="entity_type"]');

//         const selects = await adminPage.$$("select.custom-select:visible");

//         for (let select of selects) {
//             const options = await select.$$eval("option", (options) => {
//                 return options.map((option) => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex =
//                     Math.floor(Math.random() * (options.length - 1)) + 1;

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         const inputs = await adminPage.$$(
//             'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
//         );

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.click('button[class="primary-button"]:visible');

//         await expect(
//             adminPage.getByText("URL Rewrite updated successfully")
//         ).toBeVisible();
//     });

//     test("delete url rewrite", async ({ adminPage }) => {
//         await createUrlRewrite(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/url-rewrites`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconDelete = await adminPage.$$(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconDelete[0].click();

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("URL Rewrite deleted successfully")
//         ).toBeVisible();
//     });

//     test("mass delete url rewrite", async ({ adminPage }) => {
//         await createUrlRewrite(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/url-rewrites`
//         );

//         await adminPage.waitForSelector(".icon-uncheckbox");

//         const checkboxs = await adminPage.$$(".icon-uncheckbox");
//         await checkboxs[0].click();

//         await adminPage
//             .waitForSelector(
//                 'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible',
//                 { timeout: 1000 }
//             )
//             .catch(() => null);

//         await adminPage.click(
//             'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible'
//         );
//         await adminPage.click(
//             'a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible'
//         );

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Selected URL Rewrites Deleted Successfully")
//         ).toBeVisible();
//     });

//     test("create search term", async ({ adminPage }) => {
//         await createSearchTerm(adminPage);
//     });

//     test("edit search term", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/search-terms`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconEdit = await adminPage.$$(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconEdit[0].click();

//         adminPage.hover('select[name="channel_id"]');

//         const selects = await adminPage.$$("select.custom-select:visible");

//         for (let select of selects) {
//             const options = await select.$$eval("option", (options) => {
//                 return options.map((option) => option.value);
//             });

//             if (options.length > 1) {
//                 const randomIndex =
//                     Math.floor(Math.random() * (options.length - 1)) + 1;

//                 await select.selectOption(options[randomIndex]);
//             } else {
//                 await select.selectOption(options[0]);
//             }
//         }

//         const inputs = await adminPage.$$(
//             'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
//         );

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         adminPage.fill(
//             'input[name="redirect_url"]:visible',
//             forms.generateRandomUrl()
//         );

//         await adminPage.click('button[class="primary-button"]:visible');

//         await expect(
//             adminPage.getByText("Search Term updated successfully")
//         ).toBeVisible();
//     });

//     test("delete search term", async ({ adminPage }) => {
//         await createSearchTerm(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/search-terms`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconDelete = await adminPage.$$(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconDelete[0].click();

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Search Term deleted successfully")
//         ).toBeVisible();
//     });

//     test("mass delete search term", async ({ adminPage }) => {
//         await createSearchTerm(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/search-terms`
//         );

//         await adminPage.waitForSelector(".icon-uncheckbox");

//         const checkboxs = await adminPage.$$(".icon-uncheckbox");
//         await checkboxs[1].click();

//         await adminPage
//             .waitForSelector(
//                 'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible',
//                 { timeout: 1000 }
//             )
//             .catch(() => null);

//         await adminPage.click(
//             'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible'
//         );
//         await adminPage.click(
//             'a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible'
//         );

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Selected Search Terms Deleted Successfully")
//         ).toBeVisible();
//     });

//     test("create search synonym", async ({ adminPage }) => {
//         await createSearchSynonym(adminPage);
//     });

//     test("edit search synonym", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/search-synonyms`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconEdit = await adminPage.$$(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconEdit[0].click();

//         adminPage.hover('input[name="name"]');

//         const inputs = await adminPage.$$(
//             'textarea.rounded-md:visible, input[type="text"].rounded-md:visible'
//         );

//         for (let input of inputs) {
//             await input.fill(forms.generateRandomStringWithSpaces(200));
//         }

//         await adminPage.click('button[class="primary-button"]:visible');

//         await expect(
//             adminPage.getByText("Search Synonym updated successfully")
//         ).toBeVisible();
//     });

//     test("delete search synonym", async ({ adminPage }) => {
//         await createSearchSynonym(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/search-synonyms`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconDelete = await adminPage.$$(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconDelete[0].click();

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Search Synonym deleted successfully")
//         ).toBeVisible();
//     });

//     test("mass delete Search Synonym", async ({ adminPage }) => {
//         await createSearchSynonym(adminPage);

//         await adminPage.goto(
//             `admin/marketing/search-seo/search-synonyms`
//         );

//         await adminPage.waitForSelector(".icon-uncheckbox");

//         const checkboxs = await adminPage.$$(".icon-uncheckbox");
//         await checkboxs[1].click();

//         await adminPage
//             .waitForSelector(
//                 'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible',
//                 { timeout: 1000 }
//             )
//             .catch(() => null);

//         await adminPage.click(
//             'button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible'
//         );
//         await adminPage.click(
//             'a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible'
//         );

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Selected Search Synonyms Deleted Successfully")
//         ).toBeVisible();
//     });

//     test("create sitemap", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/sitemaps`
//         );

//         await adminPage.click("div.primary-button:visible");

//         adminPage.hover('input[name="file_name"]');

//         const concatenatedNames = Array(5)
//             .fill(null)
//             .map(() => forms.generateRandomProductName())
//             .join(" ")
//             .replaceAll(" ", "");

//         await adminPage.fill(
//             'input[name="file_name"]',
//             concatenatedNames + ".xml"
//         );
//         await adminPage.fill('input[name="path"]', "/");

//         await adminPage.click('button[class="primary-button"]:visible');

//         await expect(
//             adminPage.getByText("Sitemap created successfully")
//         ).toBeVisible();
//     });

//     test("edit sitemap", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/sitemaps`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconEdit = await adminPage.$$(
//             'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconEdit[0].click();

//         adminPage.hover('input[name="file_name"]');

//         const concatenatedNames = Array(5)
//             .fill(null)
//             .map(() => forms.generateRandomProductName())
//             .join(" ")
//             .replaceAll(" ", "");

//         await adminPage.fill(
//             'input[name="file_name"]',
//             concatenatedNames + ".xml"
//         );
//         await adminPage.fill('input[name="path"]', "/");

//         await adminPage.click('button[class="primary-button"]:visible');

//         await expect(
//             adminPage.getByText("Sitemap Updated successfully")
//         ).toBeVisible();
//     });

//     test("delete Sitemap", async ({ adminPage }) => {
//         await adminPage.goto(
//             `admin/marketing/search-seo/sitemaps`
//         );

//         await adminPage.waitForSelector(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         const iconDelete = await adminPage.$$(
//             'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]'
//         );

//         await iconDelete[0].click();

//         await adminPage.click(
//             "button.transparent-button + button.primary-button:visible"
//         );

//         await expect(
//             adminPage.getByText("Sitemap Deleted successfully")
//         ).toBeVisible();
//     });
});
});
