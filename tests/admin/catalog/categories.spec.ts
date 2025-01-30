import { test, expect, config } from '../../utils/setup';

test('Create Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.getByRole('link', { name: 'Create Category' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_SADASDWE');
    await page.locator('label').filter({ hasText: 'Root' }).locator('span').click();
    await page.locator('label').filter({ hasText: /^Men$/ }).locator('span').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByRole('paragraph').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').fill('Demo_ASDAWSD');
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').press('CapsLock');
    await page.getByPlaceholder('Meta Title').click();
    await page.getByPlaceholder('Meta Title').fill('Demo_sdwewew');
    await page.getByPlaceholder('Slug').click();
    await page.getByPlaceholder('Slug').fill('Demo_sadasdwewewe');
    await page.getByPlaceholder('Meta Keywords').click();
    await page.getByPlaceholder('Meta Keywords').fill('Demo_qrqwe');
    await page.getByPlaceholder('Meta Description').click();
    await page.getByPlaceholder('Meta Description').fill('Demo_wer343');
    await page.locator('#Price').nth(1).click();
    await page.locator('#Color').nth(1).click();
    await page.locator('#Size').nth(1).click();
    await page.locator('.relative > label').click();
    await page.locator('#display_mode').selectOption('products_only');
    await page.locator('#display_mode').selectOption('products_and_description');
    await page.getByPlaceholder('Enter Position').click();
    await page.getByPlaceholder('Enter Position').fill('34');
    await page.getByRole('button', { name: 'Save Category' }).click();

    try {
        const getError = await page.waitForSelector('.text-red-600.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-600.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });
            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.underline');
            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});

test('Edit Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_SADASDWE');
    await page.locator('label').filter({ hasText: 'Root' }).locator('span').click();
    await page.locator('label').filter({ hasText: /^Men$/ }).locator('span').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByRole('paragraph').click();
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').fill('Demo_ASDAWSD');
    await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').press('CapsLock');
    await page.getByPlaceholder('Meta Title').click();
    await page.getByPlaceholder('Meta Title').fill('Demo_sdwewew');
    await page.getByPlaceholder('Slug').click();
    await page.getByPlaceholder('Slug').fill('Demo_sadasdwewewe');
    await page.getByPlaceholder('Meta Keywords').click();
    await page.getByPlaceholder('Meta Keywords').fill('Demo_qrqwe');
    await page.getByPlaceholder('Meta Description').click();
    await page.getByPlaceholder('Meta Description').fill('Demo_wer343');
    await page.locator('#Price').nth(1).click();
    await page.locator('#Color').nth(1).click();
    await page.locator('#Size').nth(1).click();
    await page.locator('.relative > label').click();
    await page.locator('#display_mode').selectOption('products_only');
    await page.locator('#display_mode').selectOption('products_and_description');
    await page.getByPlaceholder('Enter Position').click();
    await page.getByPlaceholder('Enter Position').fill('34');
    await page.locator('div').filter({ hasText: /^4SADASDWE34Active0$/ }).locator('span').nth(2).click();
    await page.locator('.icon-delete').first().click();
    await page.locator('.icon-delete').click();
    await page.locator('#Brand').nth(1).click();
    await page.getByRole('button', { name: 'Save Category' }).click();

    try {
        const getError = await page.waitForSelector('.text-red-600.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-600.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });
            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.underline');
            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});

test('Delete Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.locator('div').filter({ hasText: /^4SADASDWE34Active0$/ }).locator('span').nth(3).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.underline');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});

test('Mass Delete Categories', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.locator('div').filter({ hasText: /^3Winter Wear1Active11$/ }).locator('label span').click();
    await page.locator('div').filter({ hasText: /^2Men1Active2$/ }).locator('label span').click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.underline');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});

test('Mass Update Categories', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Categories' }).click();
    await page.locator('div').filter({ hasText: /^3Winter Wear1Active11$/ }).locator('label span').click();
    await page.locator('div').filter({ hasText: /^2Men1Active2$/ }).locator('label span').click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Active', exact: true }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
    await page.getByRole('link', { name: 'Attributes' }).click();

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.underline');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});
