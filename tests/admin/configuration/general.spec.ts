import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('General of General', async () => {
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

    await page.goto(`${baseUrl}/admin/configuration/general/general`);

    console.log('General of General');

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

    await page.goto(`${baseUrl}/admin/configuration/general/content`);

    console.log('Content of General');

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

    await page.goto(`${baseUrl}/admin/configuration/general/design`);

    console.log('Design of General');

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

    await page.goto(`${baseUrl}/admin/configuration/general/magic_ai`);

    console.log('Magic AI of General');

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
