import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('customer configuration', () => {
    test('Address of Customer', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/address');

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await adminPage.fill('input[type="number"]:visible', (Math.random() * 4).toString(), { timeout: 3000 }).catch(() => null);
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Captcha of Customer', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/captcha');

        let i = Math.floor(Math.random() * 10) + 1;


        const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

        for (let input of inputs) {

            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 != 1) {
                await input.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Settings of Customer', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/settings');

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
});
