import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('currency management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);

        await page.goto(`${config.baseUrl}/admin/settings/currencies`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create currency', async () => {
        await page.click('button[type="button"].primary-button:visible');

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="code"]', (forms.generateRandomProductName() + forms.generateRandomProductName()).slice(0, 3));

        const select = await page.$('select[name="currency_position"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.press('input[name="code"]', 'Enter');

        await expect(page.getByText('Currency created successfully.')).toBeVisible();
    });

    test('edit currency', async () => {
        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        await page.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

        const select = await page.$('select[name="currency_position"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.press('input[name="code"]', 'Enter');

        await expect(page.getByText('Currency updated successfully.')).toBeVisible();
    });

    test('delete currency', async () => {
        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Currency deleted successfully.')).toBeVisible();
    });
});
