import { test, expect, config } from '../../utils/setup';
import logIn from '../../utils/admin/loginHelper';
import * as forms from '../../utils/admin/formHelper';

const { chromium, firefox, webkit } = await import('playwright');
const baseUrl = config.baseUrl;

let browser;
let context;
let page;


test('Create Customer', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.click('button.primary-button:visible');

    await page.fill('input[name="first_name"]:visible', forms.form.firstName);
    await page.fill('input[name="last_name"]:visible', forms.form.lastName);
    const email = forms.form.email;
    await page.fill('input[name="email"]:visible', email);
    await page.fill('input[name="phone"]:visible', forms.form.phone);
    await page.selectOption('select[name="gender"]:visible', 'Other');

    await page.press('input[name="phone"]:visible', 'Enter');

    await expect(page.getByText('Customer created successfully')).toBeVisible();
});

test('Edit Customer', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.click('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    await page.fill('input[name="first_name"]:visible', forms.form.firstName);
    await page.fill('input[name="last_name"]:visible', forms.form.lastName);
    const email = forms.form.email;
    await page.fill('input[name="email"]:visible', email);
    await page.fill('input[name="phone"]:visible', forms.form.phone);
    await page.selectOption('select[name="gender"]:visible', 'Other');

    const checkboxs = await page.$$('input[type="checkbox"] + label');

    for (let checkbox of checkboxs) {
        let i = Math.floor(Math.random() * 10) + 1;

        if (i % 2 == 1) {
            await checkbox.click();
        }
    }

    await page.press('input[name="phone"]:visible', 'Enter');

    await expect(page.getByText('Customer Updated Successfully')).toBeVisible();
});

test('Add Address', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.waitForSelector('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    const createBtn = await page.$$('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    await createBtn[1].click();

    await page.fill('input[name="company_name"]', forms.form.lastName);
    await page.fill('input[name="first_name"]', forms.form.firstName);
    await page.fill('input[name="last_name"]', forms.form.lastName);
    await page.fill('input[name="email"]', forms.form.email);
    await page.fill('input[name="address[0]"]', forms.form.firstName);
    await page.selectOption('select[name="country"]', 'IN');
    await page.selectOption('select[name="state"]', 'UP');
    await page.fill('input[name="city"]', forms.form.lastName);
    await page.fill('input[name="postcode"]', '201301');
    await page.fill('input[name="phone"]', forms.form.phone);

    await page.click('input[name="default_address"] + label:visible');
    await page.press('input[name="phone"]', 'Enter');

    await expect(page.getByText('Address Created Successfully')).toBeVisible();
});

test('Edit Address', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.waitForSelector('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    const createBtn = await page.$$('p[class="cursor-pointer text-blue-600 transition-all hover:underline"]:visible');

    if (createBtn.length == 0) {
        throw new Error('No address found for edit');
    }

    await createBtn[0].click();

    await page.fill('input[name="company_name"]', forms.form.lastName);
    await page.fill('input[name="first_name"]', forms.form.firstName);
    await page.fill('input[name="last_name"]', forms.form.lastName);
    await page.fill('input[name="email"]', forms.form.email);
    await page.fill('input[name="address[0]"]', forms.form.firstName);
    await page.selectOption('select[name="country"]', 'IN');
    await page.selectOption('select[name="state"]', 'UP');
    await page.fill('input[name="city"]', forms.form.lastName);
    await page.fill('input[name="postcode"]', '201301');
    await page.fill('input[name="phone"]', forms.form.phone);

    await page.click('input[name="default_address"] + label:visible');
    await page.press('input[name="phone"]', 'Enter');

    await expect(page.getByText('Address Updated Successfully')).toBeVisible();
});

test('Set Default Address', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.waitForSelector('div[class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"]:visible');

    const createBtn = await page.$$('button[class="flex cursor-pointer justify-center text-sm text-blue-600 transition-all hover:underline"]:visible');

    if (createBtn.length == 0) {
        throw new Error('No address found for edit');
    }

    await createBtn[createBtn.length - 1].click();

    await expect(page.getByText('Default Address Updated Successfully')).toBeVisible();
});

test('Delete Address', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.waitForSelector('p[class="cursor-pointer text-red-600 transition-all hover:underline"]:visible');
    await page.locator('p.text-red-600').click();

    await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

    await expect(page.getByText('Address Deleted Successfully')).toBeVisible();
});

test('Add Note', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    const lorem100 = forms.generateRandomStringWithSpaces(500);
    page.fill('textarea[name="note"]', lorem100);

    await page.click('input[name="customer_notified"] + span');

    await page.click('button[type="submit"].secondary-button:visible');

    await expect(page.getByText('Note Created Successfully')).toBeVisible();
});

test('Delete Account', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.click('.icon-cancel:visible');

    await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

    await expect(page.getByText('Customer Deleted Successfully')).toBeVisible();
});

test('Create Order', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconRight = await page.$$('a[class="icon-sort-right rtl:icon-sort-left cursor-pointer p-1.5 text-2xl hover:rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1"]');

    await iconRight[0].click();

    await page.click('.icon-cart:visible');

    await page.click('button[type="button"].transparent-button + button[type="button"].primary-button');

    await expect(page.getByText('Cart Items').first()).toBeVisible();
});

test('login as Customer', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const iconEdit = await page.$$('.icon-login');

    await iconEdit[Math.floor(Math.random() * ((iconEdit.length - 1) - 0 + 1)) + 0].click();

    await expect(page.getByText('First Name')).toBeVisible();
});

test('Mass Delete Customers', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox');

    await checkboxs[0].click();

    const button = await page.waitForSelector('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible', { timeout: 1000 }).catch(() => null);

    await page.click('button[class="inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-2 rounded-md border bg-white px-2.5 py-1.5 text-center leading-6 text-gray-600 transition-all marker:shadow hover:border-gray-400 focus:border-gray-400 focus:ring-black dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"]:visible');
    await page.click('a[class="whitespace-no-wrap flex gap-1.5 rounded-b px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950"]:visible');

    await page.click('button.transparent-button + button.primary-button:visible');

    await expect(page.getByText('Selected data successfully deleted')).toBeVisible();
});

test('Mass Update Customers', async () => {
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

    await page.goto(`${baseUrl}/admin/customers`);

    await page.waitForTimeout(5000);

    const checkboxs = await page.$$('.icon-uncheckbox');

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

    await expect(page.getByText('Selected Customers successfully updated')).toBeVisible();
});
