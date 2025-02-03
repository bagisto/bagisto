import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Channel', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/channels`);

    console.log('Create Channel');

    await page.click('a.primary-button:visible');

    await page.click('select.custom-select');

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * options.length) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

    await page.fill('input[name="code"].rounded-md:visible', concatenatedNames);

    const checkboxs = await page.$$('input[type="checkbox"] + label');

    for (let checkbox of checkboxs) {
        await checkbox.click();
    }

    await inputs[0].press('Enter');

    await expect(page.getByText('Channel created successfully.')).toBeVisible();
});

test('Edit Channel', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/channels`);

    console.log('Edit Channel');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.click('select.custom-select');

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * options.length) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        if (input == inputs[0]) {
            continue;
        }

        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const checkboxs = await page.$$('input[type="checkbox"] + label');

    for (let checkbox of checkboxs) {
        await checkbox.click();
    }

    await inputs[1].press('Enter');

    await expect(page.getByText('Update Channel Successfully')).toBeVisible();
});

test('Delete Channel', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/channels`);

    console.log('Delete Channel');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Channel deleted successfully.')).toBeVisible();
});
