import { test, expect, config } from '../../utils/setup';

test('Create Users', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Users' }).click();
    await page.getByRole('button', { name: 'Create User' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('User');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('User@gmail.com');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill('User');
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill('User');
    await page.locator('.relative > label').click();
    await page.locator('select[name="role_id"]').selectOption('1');
    await page.getByRole('button', { name: 'Save User' }).click();
    await page.locator('div').filter({ hasText: /^2KUserActiveUser@gmail\.comAdministrator$/ }).locator('a').first().click();

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

test('Edit Users', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Users' }).click();
    await page.locator('div').filter({ hasText: /^2KUserActiveUser@gmail\.comAdministrator$/ }).locator('a').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('User');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('User@gmail.com');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill('User');
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill('User');
    await page.locator('.relative > label').click();
    await page.locator('select[name="role_id"]').selectOption('1');
    await page.getByRole('button', { name: 'Save User' }).click();

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

test('Delete Users', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Users' }).click();
    await page.locator('div').filter({ hasText: /^2KUserActiveUser@gmail\.comAdministrator$/ }).locator('a').nth(1).click();
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
