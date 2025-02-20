import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('sales configuration', () => {
    test('shipping settings of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/shipping');

        await adminPage.click('select[name="sales[shipping][origin][country]"]');

        const select = await adminPage.$('select[name="sales[shipping][origin][country]"]');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }

        const state = await adminPage.$('select[name="sales[shipping][origin][state]"]');

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

        await adminPage.fill('input[name="sales[shipping][origin][zipcode]"]', '123456');
        await adminPage.fill('input[name="sales[shipping][origin][vat_number]"]', '1234567890');
        await adminPage.fill('input[name="sales[shipping][origin][contact]"]', '1234567890');

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('shipping methods of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/carriers');

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const select = await adminPage.$('select.custom-select');

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            const options = await select.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 0) {
                const randomIndex = Math.floor(Math.random() * options.length);

                await select.selectOption(options[randomIndex]);
            }
        }
        await adminPage.fill('input[name="sales[carriers][flatrate][default_rate]"]', '12');

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('payment methods of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/payment_methods');

        const files = await adminPage.$$('input[type="file"]');

        for (let file of files) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                const filePath = forms.getRandomImageFile();

                await file.setInputFiles(filePath);
            }
        }

        const deletes = await adminPage.$$('input[type="checkbox"] + label.icon-uncheckbox:visible');

        for (let checkbox of deletes) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                const options = await select.$$eval('option', (options) => {
                    return options.map(option => option.value);
                });

                if (options.length > 0) {
                    const randomIndex = Math.floor(Math.random() * options.length);

                    await select.selectOption(options[randomIndex]);
                }
            }
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('order settings of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/order_settings');

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await input.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        await adminPage.fill('input[name="sales[order_settings][order_number][order_number_length]"]', '5');
        await adminPage.fill('input[type="number"]', (Math.floor(Math.random() * 1000000)).toString(), { timeout: 3000 }).catch(() => null);

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('invoice settings of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/invoice_settings');

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await input.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        await adminPage.fill('input[name="sales[invoice_settings][invoice_number][invoice_number_length]"]', '5');

        const newErrors = await adminPage.$$('input[class="border !border-red-600 hover:border-red-600 w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');

        for (let error of newErrors) {
            await error.fill((Math.floor(Math.random() * 1000000000000000)).toString());
        }

        const file = await adminPage.$('input[type="file"]');

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            const filePath = forms.getRandomImageFile();

            await file.setInputFiles(filePath);
        }

        const select = await adminPage.$('select.custom-select');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('taxes of sales', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/taxes');

        await adminPage.click('select.custom-select');

        const selects = await adminPage.$$('select.custom-select');

        for (let select of selects) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                const options = await select.$$eval('option', (options) => {
                    return options.map(option => option.value);
                });

                if (options.length > 0) {
                    const randomIndex = Math.floor(Math.random() * options.length);

                    await select.selectOption(options[randomIndex]);
                }
            }
        }

        const state = await adminPage.$('select[name="sales[taxes][default_destination_calculation][state]"]');

        if (state) {
            const options = await state.$$eval('option', (options) => {
                return options.map(option => option.value);
            });

            if (options.length > 1) {
                const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

                await state.selectOption(options[randomIndex]);
            } else {
                await state.selectOption(options[0]);
            }
        }

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('checkout of customer', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/checkout');

        const select = await adminPage.$('select.custom-select');

        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }

        await adminPage.fill('input[name="sales[checkout][mini_cart][offer_info]"]', forms.generateRandomStringWithSpaces(200));

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
