import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Settings of Email', async () => {
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

    await page.goto(`${baseUrl}/admin/configuration/emails/configure`);

    console.log('Settings of Email');

    await page.click('input[type="text"].rounded-md:visible');

    const inputs = await page.$$('input[type="text"].rounded-md:visible');

    let i = 0;

    for (let input of inputs) {
        if (i % 2 == 0) {
            await input.fill(forms.generateRandomStringWithSpaces(50));
        } else {
            await input.fill(forms.form.email);
        }

        i++;
    }

    await page.click('button[type="submit"].primary-button:visible');

    await expect(page.getByText('Configuration saved successfully')).toBeVisible();
});

test('Notifications of Email', async () => {
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

    await page.goto(`${baseUrl}/admin/configuration/emails/general`);

    console.log('Notifications of Email');

    await page.click('button[type="submit"].primary-button:visible');

    await expect(page.getByText('Configuration saved successfully')).toBeVisible();
});
