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
    const randomEmail = `testUser${Date.now()}@gmail.com`;
    await page.getByPlaceholder('email@example.com').fill(randomEmail);
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill('testUser@123');
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill('testUser@123');
    await page.locator('#main form div').filter({ hasText: 'Subscribe to newsletter' }).locator('label').first().click();
    await page.getByRole('button', { name: 'Register' }).click();

    await page.waitForSelector('text=Account created successfully, an e-mail has been sent for verification.');
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

    await page.getByLabel('Profile').click();
    await page.waitForSelector('text=Logout');
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

    await page.getByLabel('Profile').click({timeout: 5000});
    await page.waitForSelector('text=Welcome Guest');
});
