import { test, expect, config } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('inventory source management', () => {
    test('create inventory sources', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/inventory-sources`);

        await adminPage.click('div.primary-button:visible');

        await adminPage.click('select[name="country"]');

        const select = await adminPage.$('select[name="country"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        const state = await adminPage.$('select[name="state"]');

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

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await adminPage.fill('input[type="email"].rounded-md:visible', forms.form.email);

        await adminPage.fill('input[name="contact_number"].rounded-md:visible', forms.form.phone);

        await adminPage.fill('input[name="latitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

        await adminPage.fill('input[name="longitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

        await adminPage.fill('input[name="priority"].rounded-md:visible', (Math.random() * 10000).toString());

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', ', -');

        await adminPage.fill('input[name="street"].rounded-md:visible', concatenatedNames);

        await adminPage.fill('input[name="code"].rounded-md:visible', concatenatedNames.replaceAll(', -', ''));

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await adminPage.click('input[type="checkbox"] + label.peer');
        }

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('Inventory Source Created Successfully')).toBeVisible();
    });

    test('edit inventory sources', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/inventory-sources`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await adminPage.click('select[name="country"]');

        const select = await adminPage.$('select[name="country"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        const state = await adminPage.$('select[name="state"]');

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

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await adminPage.fill('input[type="email"].rounded-md:visible', forms.form.email);

        await adminPage.fill('input[name="contact_number"].rounded-md:visible', forms.form.phone);

        await adminPage.fill('input[name="latitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

        await adminPage.fill('input[name="longitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

        await adminPage.fill('input[name="priority"].rounded-md:visible', (Math.random() * 10000).toString());

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', ', -');

        await adminPage.fill('input[name="street"].rounded-md:visible', concatenatedNames);

        await adminPage.fill('input[name="code"].rounded-md:visible', concatenatedNames.replaceAll(', -', ''));

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await adminPage.click('input[type="checkbox"] + label.peer');
        }

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('Inventory Sources Updated Successfully')).toBeVisible();
    });

    test('delete inventory sources', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/inventory-sources`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Inventory Sources Deleted Successfully')).toBeVisible();
    });
});
