import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Inventory Sources', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/inventory-sources`);

    console.log('Create Inventory Sources');

    await page.click('div.primary-button:visible');

    await page.click('select[name="country"]');

    const select = await page.$('select[name="country"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    const state = await page.$('select[name="state"]');

    if (state) {
        const options = await state.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await state.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    await page.fill('input[type="email"].rounded-md:visible', forms.form.email);

    await page.fill('input[name="contact_number"].rounded-md:visible', forms.form.phone);

    await page.fill('input[name="latitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

    await page.fill('input[name="longitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

    await page.fill('input[name="priority"].rounded-md:visible', (Math.random() * 10000).toString());

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', ', -');

    await page.fill('input[name="street"].rounded-md:visible', concatenatedNames);

    await page.fill('input[name="code"].rounded-md:visible', concatenatedNames.replaceAll(', -', ''));

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await page.click('input[type="checkbox"] + label.peer');
    }

    await inputs[0].press('Enter');

    await expect(page.getByText('Inventory Source Created Successfully')).toBeVisible();
});

test('Edit Inventory Sources', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/inventory-sources`);

    console.log('Edit Inventory Sources');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.click('select[name="country"]');

    const select = await page.$('select[name="country"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

        await select.selectOption(options[randomIndex]);
    } else {
        await select.selectOption(options[0]);
    }

    const state = await page.$('select[name="state"]');

    if (state) {
        const options = await state.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await state.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    await page.fill('input[type="email"].rounded-md:visible', forms.form.email);

    await page.fill('input[name="contact_number"].rounded-md:visible', forms.form.phone);

    await page.fill('input[name="latitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

    await page.fill('input[name="longitude"].rounded-md:visible', '-' + (Math.random() * 90).toString());

    await page.fill('input[name="priority"].rounded-md:visible', (Math.random() * 10000).toString());

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', ', -');

    await page.fill('input[name="street"].rounded-md:visible', concatenatedNames);

    await page.fill('input[name="code"].rounded-md:visible', concatenatedNames.replaceAll(', -', ''));

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await page.click('input[type="checkbox"] + label.peer');
    }

    await inputs[0].press('Enter');

    await expect(page.getByText('Inventory Sources Updated Successfully')).toBeVisible();
});

test('Delete Inventory Sources', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/inventory-sources`);

    console.log('Delete Inventory Sources');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Inventory Sources Deleted Successfully')).toBeVisible();
});
