import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Group', async () => {
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

    await page.goto(`${baseUrl}/admin/groups`);

    await page.click('button.primary-button:visible');

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
    await page.fill('input[name="code"]', concatenatedNames);

    await page.press('input[name="code"]:visible', 'Enter');

    await expect(page.getByText('Group created successfully')).toBeVisible();
});

test('Edit Group', async () => {
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

    await page.goto(`${baseUrl}/admin/groups`);

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[0].click();

    const concatenatedNames = Array(5)
    .fill(null)
    .map(() => forms.generateRandomProductName())
    .join(' ')
    .replaceAll(' ', '');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
    await page.fill('input[name="code"]', concatenatedNames);

    await page.press('input[name="code"]:visible', 'Enter');

    await expect(page.getByText('Group Updated Successfully')).toBeVisible();
});

test('Delete Group', async () => {
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

    await page.goto(`${baseUrl}/admin/groups`);

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Group Deleted Successfully')).toBeVisible();
});
