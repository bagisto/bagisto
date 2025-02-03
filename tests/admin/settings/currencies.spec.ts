import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Currency', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/currencies`);

    console.log('Create Currency');

    await page.click('button[type="button"].primary-button:visible');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="code"]', (forms.generateRandomProductName() + forms.generateRandomProductName()).slice(0, 3));

    const select = await page.$('select[name="currency_position"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    await page.press('input[name="code"]', 'Enter');

    await expect(page.getByText('Currency created successfully.')).toBeVisible();
});

test('Edit Currency', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/currencies`);

    console.log('Edit Currency');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[0].click();

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="symbol"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="decimal"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="group_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    await page.fill('input[name="decimal_separator"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    const select = await page.$('select[name="currency_position"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    await page.press('input[name="code"]', 'Enter');

    await expect(page.getByText('Currency updated successfully.')).toBeVisible();
});

test('Delete Currency', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/currencies`);

    console.log('Delete Currency');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Currency deleted successfully.')).toBeVisible();
});
