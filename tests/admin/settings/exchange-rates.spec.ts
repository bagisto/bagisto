import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Exchange Rate', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/exchange-rates`);

    console.log('Create Exchange Rate');

    await page.click('button[type="button"].primary-button:visible');

    await page.click('select[name="target_currency"]');

    const select = await page.$('select[name="target_currency"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    await page.fill('input[name="rate"]', (Math.random() * 500).toString());
    await page.press('input[name="rate"]', 'Enter');

    await expect(page.getByText('Exchange Rate Created Successfully')).toBeVisible();
});

test('Edit Exchange Rate', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/exchange-rates`);

    console.log('Edit Exchange Rate');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[0].click();

    await page.click('select[name="target_currency"]');

    const select = await page.$('select[name="target_currency"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    await page.fill('input[name="rate"]', (Math.random() * 500).toString());
    await page.press('input[name="rate"]', 'Enter');

    await expect(page.getByText('Exchange Rate Updated Successfully')).toBeVisible();
});

test('Delete Exchange Rate', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/exchange-rates`);

    console.log('Delete Exchange Rate');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Exchange Rate Deleted Successfully')).toBeVisible();
});
