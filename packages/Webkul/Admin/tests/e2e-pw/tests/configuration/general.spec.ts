import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('general configuration', () => {
    test('General of General', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/general');

        await adminPage.click('select.custom-select');

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

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Content of General', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/content');



        await adminPage.click('input[type="text"].rounded-md:visible');

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {

            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 != 1) {
                await input.fill(forms.generateRandomStringWithSpaces(25));
            }
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Design of General', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/design');

        await adminPage.click('input[type="file"]');

        const inputs = await adminPage.$$('input[type="file"]');

        for (let input of inputs) {
            const filePath = forms.getRandomImageFile();

            await input.setInputFiles(filePath);
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + label.icon-uncheckbox:visible');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Magic AI of General', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/magic_ai');

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

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
