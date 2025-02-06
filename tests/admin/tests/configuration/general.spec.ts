import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('general configuration', () => {
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

    test('General of General', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/general/general`);



        await page.click('select.custom-select');

        const select = await page.$('select.custom-select');

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

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Content of General', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/general/content`);



        await page.click('input[type="text"].rounded-md:visible');

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {

            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 != 1) {
                await input.fill(forms.generateRandomStringWithSpaces(25));
            }
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Design of General', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/general/design`);



        await page.click('input[type="file"]');

        const inputs = await page.$$('input[type="file"]');

        for (let input of inputs) {
            const filePath = forms.getRandomImageFile();

            await input.setInputFiles(filePath);
        }

        const checkboxs = await page.$$('input[type="checkbox"] + label.icon-uncheckbox:visible');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 == 1) {
                await checkbox.click();
            }
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Magic AI of General', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/general/magic_ai`);



        const selects = await page.$$('select.custom-select');

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

        const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });
});
