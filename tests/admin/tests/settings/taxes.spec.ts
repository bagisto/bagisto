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

    test('create tax rate', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/rates`);

        await page.click('a.primary-button:visible');

        page.click('select[name="country"]');

        const select = await page.$('select[name="country"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        const state = await page.$('select[name="state"]');

        if (state) {
            const options = await state.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await state.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const checkbox = await page.$('input[type="checkbox"] + label.peer:visible');

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.fill('input[name="tax_rate"]', Math.floor(Math.random() * 99).toString());

        await inputs[0].press('Enter');

        await expect(page.getByText('Tax rate created successfully.')).toBeVisible();
    });

    test('edit tax rate', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/rates`);

        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        page.click('select[name="country"]');

        const select = await page.$('select[name="country"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        const state = await page.$('select[name="state"]');

        if (state) {
            const options = await state.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await state.selectOption(options[randomIndex]);
            } else {
                await select.selectOption(options[0]);
            }
        }

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.fill('input[name="tax_rate"]', Math.floor(Math.random() * 99).toString());

        await inputs[0].press('Enter');

        await expect(page.getByText('Tax Rate Update Successfully')).toBeVisible();
    });

    test('delete tax rate', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/rates`);

        await page.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Tax rate deleted successfully')).toBeVisible();
    });

    test('create tax category', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/categories`);

        await page.click('button[type="button"].primary-button:visible');

        page.click('select[name="taxrates[]"]');

        const select = await page.$('select[name="taxrates[]"]');

        await page.evaluate((select) => {
            const options = Array.from(select.options) as HTMLOptionElement[];
            const randomCount = Math.floor(Math.random() * options.length) + 1;
            const shuffled = options.sort(() => 0.5 - Math.random());

            shuffled.slice(0, randomCount).forEach(option => {
                option.selected = true;
            });

            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
        }, select);

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await inputs[1].press('Enter');

        await expect(page.getByText('New Tax Category Created.')).toBeVisible();
    });

    test('edit tax category', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/categories`);

        await page.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        page.click('select[name="taxrates[]"]');

        const select = await page.$('select[name="taxrates[]"]');

        await page.evaluate((select) => {
            const options = Array.from(select.options) as HTMLOptionElement[];
            const randomCount = Math.floor(Math.random() * options.length) + 1;
            const shuffled = options.sort(() => 0.5 - Math.random());

            shuffled.slice(0, randomCount).forEach(option => {
                option.selected = true;
            });

            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
        }, select);

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await inputs[1].press('Enter');

        await expect(page.getByText('Tax Category Successfully Updated.')).toBeVisible();
    });

    test('delete tax category', async () => {
        await page.goto(`${config.baseUrl}/admin/settings/taxes/categories`);

        await page.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await page.click('button.transparent-button + button.primary-button:visible');

        await expect(page.getByText('Tax Category Deleted Successfully.')).toBeVisible();
    });
});
