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

        await page.goto(`${config.baseUrl}/admin/settings/roles`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create role', async () => {
        await page.click('a.primary-button:visible');

        await page.click('select[name="permission_type"]');

        const select = await page.$('select[name="permission_type"]');

        const option = Math.random() > 0.5 ? 'custom' : 'all';

        await select.selectOption({ value: option });

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await page.$$('input[type="checkbox"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await inputs[0].press('Enter');

        await expect(page.getByText('Roles Created Successfully')).toBeVisible();
    });

    test('edit role', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await page.click('select[name="permission_type"]');

        const select = await page.$('select[name="permission_type"]');

        const option = Math.random() > 0.5 ? 'custom' : 'all';

        await select.selectOption({ value: option });

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await page.$$('input[type="checkbox"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await inputs[0].press('Enter');

        await expect(page.getByText('Roles is updated successfully')).toBeVisible();
    });

    test('delete role', async () => {
        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Roles is deleted successfully')).toBeVisible();
    });
});
