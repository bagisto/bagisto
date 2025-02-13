import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('currency management', () => {
    // test('create currency', async ({ adminPage }) => {
    //     await adminPage.goto('admin/settings/currencies');

    //     await adminPage.click('button[type="button"].primary-button:visible');

    //     await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="code"]', (forms.generateRandomProductName() + forms.generateRandomProductName()).slice(0, 3));

    //     const select = await adminPage.$('select[name="currency_position"]');

    //     const options = await select.$$eval('option', (options) => {
    //         return options.map(option => option.value);
    //     });

    //     if (options.length > 1) {
    //         const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

    //         await select.selectOption(options[randomIndex]);
    //     } else {
    //         await select.selectOption(options[0]);
    //     }

    //     await adminPage.press('input[name="code"]', 'Enter');

    //     await expect(adminPage.getByText('Currency created successfully.')).toBeVisible();
    // });

    // test('edit currency', async ({ adminPage }) => {
    //     await adminPage.goto('admin/settings/currencies');

    //     await adminPage.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    //     const iconEdit = await adminPage.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    //     await iconEdit[0].click();

    //     await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     await adminPage.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    //     const select = await adminPage.$('select[name="currency_position"]');

    //     const options = await select.$$eval('option', (options) => {
    //         return options.map(option => option.value);
    //     });

    //     if (options.length > 1) {
    //         const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

    //         await select.selectOption(options[randomIndex]);
    //     } else {
    //         await select.selectOption(options[0]);
    //     }

    //     await adminPage.press('input[name="code"]', 'Enter');

    //     await expect(adminPage.getByText('Currency updated successfully.')).toBeVisible();
    // });

    // test('delete currency', async ({ adminPage }) => {
    //     await adminPage.goto('admin/settings/currencies');

    //     await adminPage.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    //     const iconDelete = await adminPage.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    //     await iconDelete[0].click();

    //     await adminPage.click('button.transparent-button + button.primary-button:visible');

    //     await expect(adminPage.getByText('Currency deleted successfully.')).toBeVisible();
    // });
});
