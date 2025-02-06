import { test, expect, config } from '../../utils/setup';
import { launchBrowser } from '../../utils/admin/coreHelper';
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

        await page.goto(`${config.baseUrl}/admin/settings/exchange-rates`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create exchange rate', async () => {
        await page.click('button[type="button"].primary-button:visible');

        await page.click('select[name="target_currency"]');

        const select = await page.$('select[name="target_currency"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.fill('input[name="rate"]', (Math.random() * 500).toString());
        await page.press('input[name="rate"]', 'Enter');

        await expect(page.getByText('Exchange Rate Created Successfully')).toBeVisible();
    });

    test('edit exchange rate', async () => {
        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await page.click('select[name="target_currency"]');

        const select = await page.$('select[name="target_currency"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.fill('input[name="rate"]', (Math.random() * 500).toString());
        await page.press('input[name="rate"]', 'Enter');

        await expect(page.getByText('Exchange Rate Updated Successfully')).toBeVisible();
    });

    test('delete exchange rate', async () => {
        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Exchange Rate Deleted Successfully')).toBeVisible();
    });
});
