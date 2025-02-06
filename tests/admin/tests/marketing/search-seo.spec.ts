import { test, expect, config } from '../../utils/setup';
import { launchBrowser } from '../../utils/admin/coreHelper';
import  * as forms from '../../utils/admin/formHelper';
import logIn from '../../utils/admin/loginHelper';

test.describe('attribute management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create url rewrite', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);

        await page.click('div.primary-button:visible');

        page.hover('select[name="entity_type"]');

        const selects = await page.$$('select.custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('URL Rewrite created successfully')).toBeVisible();
    });

    test('edit url rewrite', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);

        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconEdit[0].click();

        page.hover('select[name="entity_type"]');

        const selects = await page.$$('select.custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('URL Rewrite updated successfully')).toBeVisible();
    });

    test('delete url rewrite', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);

        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('URL Rewrite deleted successfully')).toBeVisible();
    });

    test('mass delete url rewrite', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);

        await page.waitForSelector('.icon-uncheckbox');

        const checkboxs = await page.$$('.icon-uncheckbox');
        await checkboxs[0].click();

        await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Selected URL Rewrites Deleted Successfully')).toBeVisible();
    });

    test('create search term', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);

        await page.click('div.primary-button:visible');

        page.hover('select[name="channel_id"]');

        const selects = await page.$$('select.custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        page.fill('input[name="redirect_url"]:visible', forms.generateRandomUrl());

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Search Term created successfully')).toBeVisible();
    });

    test('edit search term', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);

        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconEdit[0].click();

        page.hover('select[name="channel_id"]');

        const selects = await page.$$('select.custom-select:visible');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        page.fill('input[name="redirect_url"]:visible', forms.generateRandomUrl());

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Search Term updated successfully')).toBeVisible();
    });

    test('delete search term', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);

        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Search Term deleted successfully')).toBeVisible();
    });

    test('mass delete search term', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);

        await page.waitForSelector('.icon-uncheckbox');

        const checkboxs = await page.$$('.icon-uncheckbox');
        await checkboxs[1].click();

        await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Selected Search Terms Deleted Successfully')).toBeVisible();
    });

    test('create search synonym', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-synonyms`);

        await page.click('div.primary-button:visible');

        page.hover('input[name="name"]');

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Search Synonym created successfully')).toBeVisible();
    });

    test('edit search synonym', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-synonyms`);

        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconEdit[0].click();

        page.hover('input[name="name"]');

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Search Synonym updated successfully')).toBeVisible();
    });

    test('delete search synonym', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-synonyms`);

        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Search Synonym deleted successfully')).toBeVisible();
    });

    test('mass delete Search Synonym', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/search-synonyms`);

        await page.waitForSelector('.icon-uncheckbox');

        const checkboxs = await page.$$('.icon-uncheckbox');
        await checkboxs[1].click();

        await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

        await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
        await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Selected Search Synonyms Deleted Successfully')).toBeVisible();
    });

    test('create sitemap', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/sitemaps`);

        await page.click('div.primary-button:visible');

        page.hover('input[name="file_name"]');

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await page.fill('input[name="file_name"]', concatenatedNames + '.xml');
        await page.fill('input[name="path"]', '/');

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Sitemap created successfully')).toBeVisible();
    });

    test('edit sitemap', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/sitemaps`);

        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconEdit[0].click();

        page.hover('input[name="file_name"]');

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await page.fill('input[name="file_name"]', concatenatedNames + '.xml');
        await page.fill('input[name="path"]', '/');

        await page.click('button[class="primary-button"]:visible');

        await expect(page.getByText('Sitemap Updated successfully')).toBeVisible();
    });

    test('delete Sitemap', async () => {
        await page.goto(`${config.baseUrl}/admin/marketing/search-seo/sitemaps`);

        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Sitemap Deleted successfully')).toBeVisible();
    });
});
