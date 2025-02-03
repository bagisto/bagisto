import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Locale', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/locales`);

    console.log('Create Locale');

    await page.click('button[type="button"].primary-button:visible');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    const select = await page.$('select[name="direction"]');

    const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

    await select.selectOption({ value: option });

    const concatenatedNames = Array(5)
    .fill(null)
    .map(() => forms.generateRandomProductName())
    .join(' ')
    .replaceAll(' ', '');

    await page.fill('input[name="code"]', concatenatedNames);

    await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
        el.innerHTML += content;
    }, `<input type="file" name="logo_path[]" accept="image/*">`);

    const image = await page.$('input[type="file"][name="logo_path[]"]');

    const filePath = forms.getRandomImageFile();

    await image.setInputFiles(filePath);

    await page.press('input[name="code"]', 'Enter');

    await expect(page.getByText('Locale created successfully.')).toBeVisible();
});

test('Edit Locale', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/locales`);

    console.log('Edit Locale');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[0].click();

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));

    const select = await page.$('select[name="direction"]');

    const option = Math.random() > 0.5 ? 'ltr' : 'rtl';

    await select.selectOption({ value: option });

    await page.$eval('label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]', (el, content) => {
        el.innerHTML += content;
    }, `<input type="file" name="logo_path[]" accept="image/*">`);

    const image = await page.$('input[type="file"][name="logo_path[]"]');

    const filePath = forms.getRandomImageFile();

    await image.setInputFiles(filePath);

    await page.press('input[name="name"]', 'Enter');

    await expect(page.getByText('Locale updated successfully.')).toBeVisible();
});

test('Delete Locale', async () => {
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

    await page.goto(`${baseUrl}/admin/settings/locales`);

    console.log('Delete Locale');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Locale deleted successfully.')).toBeVisible();
});
