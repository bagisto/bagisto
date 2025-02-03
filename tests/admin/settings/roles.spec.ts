import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Role', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/roles`);

    console.log('Create Role');

    await page.click('a.primary-button:visible');

    await page.click('select[name="permission_type"]');

    const select = await page.$('select[name="permission_type"]');

    const option = Math.random() > 0.5 ? 'custom' : 'all';

    await select.selectOption({ value: option });

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const checkboxs = await page.$$('input[type="checkbox"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await checkbox.click();
        }
    }

    await inputs[0].press('Enter');

    await expect(page.getByText('Roles Created Successfully')).toBeVisible();
});

test('Edit Role', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/roles`);

    console.log('Edit Role');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.click('select[name="permission_type"]');

    const select = await page.$('select[name="permission_type"]');

    const option = Math.random() > 0.5 ? 'custom' : 'all';

    await select.selectOption({ value: option });

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const checkboxs = await page.$$('input[type="checkbox"] + span');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await checkbox.click();
        }
    }

    await inputs[0].press('Enter');

    await expect(page.getByText('Roles is updated successfully')).toBeVisible();
});

test('Delete Role', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/roles`);

    console.log('Delete Role');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Roles is deleted successfully')).toBeVisible();
});
