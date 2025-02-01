import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Address of Customer', async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/configuration/customer/address`);

    console.log('Address of Customer');

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 3 == 1) {
        await page.fill('input[type="number"]:visible', (Math.random() * 4).toString(), { timeout: 3000 }).catch(() => null);
    }

    await page.click('button[type="submit"].primary-button:visible');

    await expect(page.getByText('Configuration saved successfully')).toBeVisible();
});

test('Captcha of Customer', async () => {
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/configuration/customer/captcha`);

    console.log('Captcha of Customer');

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
    test.setTimeout(config.mediumTimeout);
    if (config.browser === 'firefox') {
        browser = await firefox.launch();
    } else if (config.browser === 'webkit') {
        browser = await webkit.launch();
    } else {
        browser = await chromium.launch();
    }

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/configuration/customer/settings`);

    console.log('Settings of Customer');

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
