import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('group management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);
        await page.goto(`${config.baseUrl}/admin/customers/groups`);
        await page.waitForSelector('div.primary-button', { state: 'visible' });
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create group', async () => {
        await page.click('button.primary-button:visible');

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
        await page.fill('input[name="code"]', concatenatedNames);

        await page.press('input[name="code"]:visible', 'Enter');

        await expect(page.getByText('Group created successfully')).toBeVisible();
    });

    test('edit group', async () => {
        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
        await page.fill('input[name="code"]', concatenatedNames);

        await page.press('input[name="code"]:visible', 'Enter');

        await expect(page.getByText('Group Updated Successfully')).toBeVisible();
    });

    test('delete group', async () => {
        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Group Deleted Successfully')).toBeVisible();
    });
});
