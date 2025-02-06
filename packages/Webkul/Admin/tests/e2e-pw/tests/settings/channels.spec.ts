import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('channel management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);

        await page.goto(`${config.baseUrl}/admin/settings/channels`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create channel', async () => {
        await page.click('a.primary-button:visible');

        await page.click('select.custom-select');

        const selects = await page.$$('select.custom-select');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * options.length) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await page.fill('input[name="code"].rounded-md:visible', concatenatedNames);

        const checkboxs = await page.$$('input[type="checkbox"] + label');

        for (let checkbox of checkboxs) {
            await checkbox.click();
        }

        await inputs[0].press('Enter');

        await expect(page.getByText('Channel created successfully.')).toBeVisible();
    });

    test('edit channel', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await page.click('select.custom-select');

        const selects = await page.$$('select.custom-select');

        for (let select of selects) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * options.length) + 1;

                await select.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            if (input == inputs[0]) {
                continue;
            }

            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await page.$$('input[type="checkbox"] + label');

        for (let checkbox of checkboxs) {
            await checkbox.click();
        }

        await inputs[1].press('Enter');

        await expect(page.getByText('Update Channel Successfully')).toBeVisible();
    });

    test('delete channel', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Channel deleted successfully.')).toBeVisible();
    });
});
