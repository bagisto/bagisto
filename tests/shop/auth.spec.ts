import { test, expect, config } from '../utils/setup';

test('register', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign Up' }).click();
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('testUser');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').fill('Demo');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill('testUser@123');
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill('testUser@123');
    await page.locator('#main form div').filter({ hasText: 'Subscribe to newsletter' }).locator('label').first().click();
    await page.getByRole('button', { name: 'Register' }).click();
    await page.getByText('Account created successfully.').first().click();

    try {
        const getError = await page.waitForSelector('.text-red-500.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-500.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.icon-cancel');

            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});

test('login', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill('testUser@123');
    await page.getByRole('button', { name: 'Sign In' }).click();

    try {
        const getError = await page.waitForSelector('.text-red-500.text-xs.italic', { timeout: 2000 }).catch(() => null);

        if (getError) {
            const errors = await page.$$eval('.text-red-500.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
        } else {
            await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

            const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
            await page.click('.cursor-pointer.icon-cancel');

            console.log(message);
        }
    } catch(e) {
        console.log(page.url());
    }
});

test('logout', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill('testUser@123');
    await page.getByRole('button', { name: 'Sign In' }).click();
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Logout' }).click();

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.icon-cancel');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});
