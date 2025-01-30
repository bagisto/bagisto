import { test, expect, config } from '../utils/setup';

test('register', async ({ page }) => {
    const randomEmail = Math.random().toString(36).substring(2, 11) + '@gmail.com';
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign Up' }).click();
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('testUser');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').fill('Demo');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill(randomEmail);
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill('testUser@123');
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill('testUser@123');
    await page.locator('#main form div').filter({ hasText: 'Subscribe to newsletter' }).locator('label').first().click();
    await page.getByRole('button', { name: 'Register' }).click();

    try {
        const successMessage = await page.waitForSelector('text=Account created successfully, an e-mail has been sent for verification', { timeout: 5000 }).catch(() => null);

        if (successMessage) {
            const message = await successMessage.innerText();
        } else {
            const errors = await page.$$eval('.text-red-500.text-xs.italic', els => els.map(el => el.innerText));
            errors.forEach(message => console.log(message));
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

    const errors = await page.$$eval('.text-red-500.text-xs.italic', els => els.map(el => el.innerText));

    if (errors.length > 0) {
        errors.forEach(message => console.log(message));
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
});
