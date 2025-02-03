import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Category', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/categories`);

    console.log('Create Categories');

    await page.click('div.primary-button:visible');

    await page.waitForSelector('input[name="name"]#name');

    await page.fill('input[name="position"]', (Math.floor(Math.random() * 100)).toString());

    const parents = await page.$$('input[name="parent_id"] + span[class="icon-radio-normal peer-checked:icon-radio-selected mr-1 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"]');

    await parents[Math.floor(Math.random() * ((parents.length - 1) - 0 + 1)) + 0].click();

    await page.waitForSelector('iframe');
    const iframe = await page.$('iframe');

    const frame = await iframe.contentFrame();

    const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);
    await frame.waitForSelector('body[data-id="description"] > p');

    await frame.$eval('body[data-id="description"] > p', (el, content) => {
        el.innerHTML = content;
    }, randomHtmlContent);

    await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
        el.innerHTML += content;
    }, `<input type="file" name="logo_path[]" accept="image/*"><input type="file" name="banner_path[]" accept="image/*">`);

    const images = await page.$$('input[type="file"][name="logo_path[]"], input[type="file"][name="banner_path[]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const description = document.querySelector('textarea[name="description"]#description');

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="description"]', randomHtmlContent.toString());

    await page.evaluate((content) => {
        const description = document.querySelector('textarea[name="description"]#description');

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const textareas = await page.$$('textarea:visible, input[name="meta_title"], input[name="meta_keywords"]');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }
    }

    const checkboxs = await page.$$('input[type="checkbox"] + label');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    await page.fill('input[name="name"]#name', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
    await page.fill('input[name="slug"]#slug', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

    await page.click('.primary-button:visible');

    await expect(page.getByText('Product updated successfully')).toBeVisible();
});

test('Edit Category', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/categories`);

    console.log('Edit Categories');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.waitForSelector('input[name="en[name]"]');

    await page.fill('input[name="position"]', (Math.floor(Math.random() * 100)).toString());

    const parents = await page.$$('input[name="parent_id"] + span[class="icon-radio-normal peer-checked:icon-radio-selected mr-1 cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"]');

    await parents[Math.floor(Math.random() * ((parents.length - 1) - 0 + 1)) + 0].click();

    await page.waitForSelector('iframe');
    const iframe = await page.$('iframe');

    const frame = await iframe.contentFrame();

    const randomHtmlContent = await forms.fillParagraphWithRandomHtml(50);

    await frame.waitForSelector('body[data-id="description"] > p');
    await frame.$eval('body[data-id="description"] > p', (el, content) => {
        el.innerHTML = content;
    }, randomHtmlContent);

    await page.$eval('p.mb-4.text-base.font-semibold.text-gray-800', (el, content) => {
        el.innerHTML += content;
    }, `<input type="file" name="logo_path[]" accept="image/*"><input type="file" name="banner_path[]" accept="image/*">`);

    const images = await page.$$('input[type="file"][name="logo_path[]"], input[type="file"][name="banner_path[]"]');

    const filePath = forms.getRandomImageFile();

    for (let image of images) {
        await image.setInputFiles(filePath);
    }

    await page.evaluate((content) => {
        const description = document.querySelector('textarea[name="en[description]"]#description');

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="en[description]"]', randomHtmlContent.toString());

    await page.evaluate((content) => {
        const description = document.querySelector('textarea[name="en[description]"]#description');

        if (description instanceof HTMLTextAreaElement) {
            description.style.display = content;
        }
    }, 'none');

    const textareas = await page.$$('textarea:visible, input[name="en[meta_title]"], input[name="en[meta_keywords]"]');

    for (let textarea of textareas) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 3 == 1) {
            await textarea.fill(forms.generateRandomStringWithSpaces(200));
        }
    }

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 0) {
            const randomIndex = Math.floor(Math.random() * options.length);

            await select.selectOption(options[randomIndex]);
        }
    }

    const checkboxs = await page.$$('input[type="checkbox"] + label');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    await page.fill('input[name="en[name]"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));
    await page.fill('input[name="en[slug]"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 100)));

    await page.click('.primary-button:visible');

    await expect(page.getByText('category updated successfully.')).toBeVisible();
});

test('Delete Category', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/categories`);

    console.log('Delete Categories');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconEdit[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('The category has been successfully deleted.')).toBeVisible();
});

test('Mass Update Categories', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/categories`);

    console.log('Mass Update Categories');

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox:visible');

    await checkboxs[1].click();

    await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

    await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
    await page.hover('a[class="whitespace-no-wrap flex cursor-not-allowed justify-between gap-1.5 rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    const buttons = await page.$$('a[class="whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await buttons[1].click();
    } else {
        await buttons[0].click();
    }

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('category updated successfully.')).toBeVisible();
});

test('Mass Delete Categories', async () => {
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

    await page.goto(`${baseUrl}/admin/catalog/categories`);

    console.log('Mass Delete Categories');

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox:visible');

    await checkboxs[1].click();

    const button = await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

    await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
    await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('The category has been successfully deleted.')).toBeVisible();
});
