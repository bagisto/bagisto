import { test, expect } from '../../setup';

test.describe('exchange rate management', () => {
    test('create exchange rate', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/exchange-rates');

        await adminPage.click('button[type="button"].primary-button:visible');

        await adminPage.click('select[name="target_currency"]');

        const select = await adminPage.$('select[name="target_currency"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await adminPage.fill('input[name="rate"]', (Math.random() * 500).toString());
        await adminPage.press('input[name="rate"]', 'Enter');

        await expect(adminPage.getByText('Exchange Rate Created Successfully')).toBeVisible();
    });

    test('edit exchange rate', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/exchange-rates');

        await adminPage.waitForSelector('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconEdit = await adminPage.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconEdit[0].click();

        await adminPage.click('select[name="target_currency"]');

        const select = await adminPage.$('select[name="target_currency"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        await adminPage.fill('input[name="rate"]', (Math.random() * 500).toString());
        await adminPage.press('input[name="rate"]', 'Enter');

        await expect(adminPage.getByText('Exchange Rate Updated Successfully')).toBeVisible();
    });

    test('delete exchange rate', async ({ adminPage }) => {
        await adminPage.goto('admin/settings/exchange-rates');

        await adminPage.waitForSelector('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        const iconDelete = await adminPage.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Exchange Rate Deleted Successfully')).toBeVisible();
    });
});
