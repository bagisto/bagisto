import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('user management', () => {
    let browser;
    let context;
    let page;

    test.beforeEach(async () => {
        browser = await launchBrowser();
        context = await browser.newContext();
        page = await context.newPage();

        await logIn(page);
        await page.goto(`${config.baseUrl}/admin/settings/users`);
    });

    test.afterEach(async () => {
        await browser.close();
    });

    test('create users', async () => {
        await page.click('button[type="button"].primary-button:visible');

        await page.click('select[name="role_id"]');

        const select = await page.$('select[name="role_id"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

        await page.fill('input[type="email"].rounded-md:visible', forms.form.email);

        const password = forms.generateRandomPassword(8, 20);

        await page.fill('input[name="password"].rounded-md:visible', password);

        await page.fill('input[name="password_confirmation"].rounded-md:visible', password);

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await page.click('input[type="checkbox"] + label.peer');
        }

        await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="image[]" accept="image/*">`);

        const image = await page.$('input[type="file"][name="image[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await page.press('input[name="name"]', 'Enter');

        await expect(page.getByText('User created successfully.')).toBeVisible();
    });

    test('edit users', async () => {
        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await page.click('select[name="role_id"]');

        const select = await page.$('select[name="role_id"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

        await page.fill('input[type="email"].rounded-md:visible', forms.form.email);

        const password = forms.generateRandomPassword(8, 20);

        await page.fill('input[name="password"].rounded-md:visible', password);

        await page.fill('input[name="password_confirmation"].rounded-md:visible', password);

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await page.click('input[type="checkbox"] + label.peer');
        }

        await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
            el.innerHTML += content;
        }, `<input type="file" name="image[]" accept="image/*">`);

        const image = await page.$('input[type="file"][name="image[]"]');

        const filePath = forms.getRandomImageFile();

        await image.setInputFiles(filePath);

        await page.press('input[name="name"]', 'Enter');

        await expect(page.getByText('User updated successfully.')).toBeVisible();
    });

    test('delete Users', async () => {
        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('User deleted successfully.')).toBeVisible();
    });
});
