import { test, expect, config } from '../../setup';
import { launchBrowser } from '../../utils/core';
import  * as forms from '../../utils/form';
import logIn from '../../utils/login';

test.describe('customer configuration', () => {
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

    test('Address of Customer', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/customer/address`);

        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await page.fill('input[type="number"]:visible', (Math.random() * 4).toString(), { timeout: 3000 }).catch(() => null);
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Captcha of Customer', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/customer/captcha`);

        let i = Math.floor(Math.random() * 10) + 1;


        const inputs = await page.$$('input[type="text"].rounded-md:visible');

        for (let input of inputs) {

            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 2 != 1) {
                await input.fill(forms.generateRandomStringWithSpaces(200));
            }
        }

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Settings of Customer', async () => {
        await page.goto(`${config.baseUrl}/admin/configuration/customer/settings`);

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

        await page.click('button[type="submit"].primary-button:visible');

        await expect(page.getByText('Configuration saved successfully')).toBeVisible();
    });
});
