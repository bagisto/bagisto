import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;

test('Create Create Template', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/email-templates`);

    console.log('Create Email Template');

    await page.click('div.primary-button:visible');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

    const select = await page.$('select[name="status"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        await select.selectOption(options[1]);
    } else {
        await select.selectOption(options[0]);
    }

    const randomHtmlContent = await forms.fillParagraphWithRandomHtml(Math.floor(Math.random() * 1000));

    await page.click('button[type="button"][title="Source code"]:visible');

    await page.fill('textarea[type="text"]:visible', randomHtmlContent.toString());

    await page.click('button[type="button"][title="Save"]:visible');

    await page.evaluate((content) => {
        const _content = document.querySelector('textarea[name="content"]#content');

        if (_content instanceof HTMLTextAreaElement) {
            _content.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="content"]', randomHtmlContent.toString());

    await page.click('button[type="submit"][class="primary-button"]:visible');

    await expect(page.getByText('Email template created successfully.')).toBeVisible();
});

test('Edit Create Template', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/email-templates`);

    console.log('Edit Email Template');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

    const select = await page.$('select[name="status"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        await select.selectOption(options[1]);
    } else {
        await select.selectOption(options[0]);
    }

    const randomHtmlContent = await forms.fillParagraphWithRandomHtml(Math.floor(Math.random() * 1000));

    await page.click('button[type="button"][title="Source code"]:visible');

    await page.fill('textarea[type="text"]:visible', randomHtmlContent.toString());

    await page.click('button[type="button"][title="Save"]:visible');

    await page.evaluate((content) => {
        const _content = document.querySelector('textarea[name="content"]#content');

        if (_content instanceof HTMLTextAreaElement) {
            _content.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="content"]', randomHtmlContent.toString());

    await page.click('button[type="submit"][class="primary-button"]:visible');

    await expect(page.getByText('Updated successfully')).toBeVisible();
});

test('Delete Template', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/email-templates`);

    console.log('Delete Email Template');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Template Deleted successfully')).toBeVisible();
});

test('Create Event', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/events`);

    console.log('Create Event');

    await page.click('div.primary-button:visible');

    page.hover('input[name="name"]');

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const time = forms.generateRandomDateTimeRange();

    await page.fill('input[name="date"]', time.from);

    await page.click('button[class="primary-button"]:visible');

    await expect(page.getByText('Events Created Successfully')).toBeVisible();
});

test('Edit Event', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/events`);

    console.log('Edit Event');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[0].click();

    const iconExists = await page.waitForSelector('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl', { timeout: 3000 }).catch(() => null);

    if (iconExists) {
        const messages = await page.$$('.flex.items-center.break-all.text-sm > .icon-toast-done.rounded-full.bg-white.text-2xl');
        const icons = await page.$$('.flex.items-center.break-all.text-sm + .cursor-pointer.underline');

        const message = await messages[0].evaluate(el => el.parentNode.innerText);
        await icons[0].click();

        throw new Error(message);
    }

    page.hover('input[name="name"]');

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const time = forms.generateRandomDateTimeRange();

    await page.fill('input[name="date"]', time.from);

    await page.click('button[class="primary-button"]:visible');

    await expect(page.getByText('Events Updated Successfully')).toBeVisible();
});

test('Delete Event', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/events`);

    console.log('Delete Event');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Events Deleted Successfully')).toBeVisible();
});

test('Create Campaign', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/email-templates`);

    console.log('Create Email Template');

    await page.click('div.primary-button:visible');

    await page.fill('input[name="name"]', forms.generateRandomStringWithSpaces(200));

    const select = await page.$('select[name="status"]');

    const options = await select.$$eval('option', (options) => {
        return options.map(option => option.value);
    });

    if (options.length > 1) {
        await select.selectOption(options[1]);
    } else {
        await select.selectOption(options[0]);
    }

    const randomHtmlContent = await forms.fillParagraphWithRandomHtml(Math.floor(Math.random() * 1000));

    await page.click('button[type="button"][title="Source code"]:visible');

    await page.fill('textarea[type="text"]:visible', randomHtmlContent.toString());

    await page.click('button[type="button"][title="Save"]:visible');

    await page.evaluate((content) => {
        const _content = document.querySelector('textarea[name="content"]#content');

        if (_content instanceof HTMLTextAreaElement) {
            _content.style.display = content;
        }
    }, 'block');

    await page.fill('textarea[name="content"]', randomHtmlContent.toString());

    await page.click('button[type="submit"][class="primary-button"]:visible');

    await page.waitForTimeout(5000);

    await page.goto(`${baseUrl}/admin/marketing/communications/events`);

    console.log('Create Event');

    await page.click('div.primary-button:visible');

    page.hover('input[name="name"]');

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const time = forms.generateRandomDateTimeRange();

    await page.fill('input[name="date"]', time.from);

    await page.click('button[class="primary-button"]:visible');

    await page.waitForTimeout(5000);

    await page.goto(`${baseUrl}/admin/marketing/communications/campaigns`);

    console.log('Create Campaign');

    await page.click('div.primary-button:visible');

    await page.click('input[type="checkbox"] + label.peer');

    const newInputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of newInputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await page.click('input[type="checkbox"] + label.peer');
    }

    await page.click('button[class="primary-button"]:visible');

    await expect(page.getByText('Campaign created successfully.')).toBeVisible();
});

test('Edit Campaign', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/campaigns`);

    console.log('Edit Campaign');

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

    await iconEdit[0].click();

    await page.click('input[type="checkbox"] + label.peer');

    const inputs = await page.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

    for (let input of inputs) {
        await input.fill(forms.generateRandomStringWithSpaces(200));
    }

    const selects = await page.$$('select.custom-select');

    for (let select of selects) {
        const options = await select.$$eval('option', (options) => {
            return options.map(option => option.value);
        });

        if (options.length > 1) {
            const randomIndex = Math.floor(Math.random() * (options.length - 1)) + 1;

            await select.selectOption(options[randomIndex]);
        } else {
            await select.selectOption(options[0]);
        }
    }

    let i = Math.floor(Math.random() * 10) + 1;

    if (i % 2 == 1) {
        await page.click('input[type="checkbox"] + label.peer');
    }

    await page.click('button[class="primary-button"]:visible');

    await expect(page.getByText('Campaign updated successfully.')).toBeVisible();
});

test('Delete Campaign', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/campaigns`);

    console.log('Delete Campaign');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Campaign deleted successfully')).toBeVisible();
});

test('Edit Newsletter Subscriber', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/subscribers`);

    console.log('Edit Newsletter Subscriber');

    const iconEdit = await page.$$('span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconEdit[Math.floor(Math.random() * ((iconEdit.length - 1) - 0 + 1)) + 0].click();

    await page.waitForSelector('select[name="is_subscribed"]');

    const select = await page.$('select[name="is_subscribed"]');

    const option = Math.random() > 0.5 ? '1' : '0';

    await select.selectOption({ value: option });

    let i = Math.floor(Math.random() * 10) + 1;

    await page.click('button[class="primary-button"]:visible');

    await expect(page.getByText('Newsletter Subscription Updated Successfully')).toBeVisible();
});

test('Delete Newsletter Subscriber', async () => {
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

    await page.goto(`${baseUrl}/admin/marketing/communications/subscribers`);

    console.log('Delete Newsletter Subscriber');

    await page.waitForTimeout(5000);

    const iconDelete = await page.$$('span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]');

    await iconDelete[0].click();

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Subscriber Deleted Successfully')).toBeVisible();
});
