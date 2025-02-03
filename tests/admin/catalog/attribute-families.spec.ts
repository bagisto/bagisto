import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Attribute Family', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/families`);

    await page.click('div.primary-button:visible');

    await page.waitForSelector('div#not_avaliable', { timeout: 1000 }).catch(() => null);

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
        .join(' ')
        .replaceAll(' ', '');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
    await page.fill('input[name="code"]', concatenatedNames);

    const attributes = await page.$$('i.icon-drag');
    const targets = await page.$$('div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]');

    for (const attribute of attributes) {

        const randomTargetIndex = Math.floor(Math.random() * targets.length);
        const target = targets[randomTargetIndex];

        const attributeBox = await attribute.boundingBox();
        const targetBox = await target.boundingBox();

        if (
            attributeBox
            && targetBox
        ) {
            const randomX = targetBox.x + Math.random() * targetBox.width;
            const randomY = targetBox.y + Math.random() * targetBox.height;

            await page.mouse.move(attributeBox.x + attributeBox.width / 2, attributeBox.y + attributeBox.height / 2);
            await page.mouse.down();
            await page.mouse.move(randomX, randomY);
            await page.mouse.up();
        }
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Family created successfully.')).toBeVisible();
});

test('Edit Attribute Family', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/families`);

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.waitForTimeout(5000);

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

    const attributes = await page.$$('i.icon-drag');
    const targets = await page.$$('div[class="flex [&>*]:flex-1 gap-5 justify-between px-4"] > div > div[class="h-[calc(100vh-285px)] overflow-auto border-gray-200 pb-4 ltr:border-r rtl:border-l"]');

    for (const attribute of attributes) {

        const randomTargetIndex = Math.floor(Math.random() * targets.length);
        const target = targets[randomTargetIndex];

        const attributeBox = await attribute.boundingBox();
        const targetBox = await target.boundingBox();

        if (
            attributeBox
            && targetBox
        ) {
            const randomX = targetBox.x + Math.random() * targetBox.width;
            const randomY = targetBox.y + Math.random() * targetBox.height;

            await page.mouse.move(attributeBox.x + attributeBox.width / 2, attributeBox.y + attributeBox.height / 2);
            await page.mouse.down();
            await page.mouse.move(randomX, randomY);
            await page.mouse.up();
        }
    }

    await page.click('.primary-button:visible');

    await expect(page.getByText('Family updated successfully.')).toBeVisible();
});

test('Delete Attribute Family', async () => {
    test.setTimeout(config.mediumTimeout);
    browser = await chromium.launch();

    // Create a new context
    context = await browser.newContext();

    // Open a new page
    page = await context.newPage();

    // Log in once
    const log = await logIn(page);
    if (log == null) {
        throw new Error('Login failed. Tests will not proceed.');
    }

    await page.goto(`${baseUrl}/admin/catalog/families`);

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Family deleted successfully.')).toBeVisible();
});
