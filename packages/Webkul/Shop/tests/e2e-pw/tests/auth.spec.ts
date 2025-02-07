import { test, expect, config } from '../setup';

async function register(page) {
    const credentials = {
        email: `testUser${Date.now()}@gmail.com`,
        password: 'testUser@123'
    };

    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign Up' }).click();
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('testUser');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').fill('Demo');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill(credentials.email);
    await page.getByPlaceholder('Password', { exact: true }).click();
    await page.getByPlaceholder('Password', { exact: true }).fill(credentials.password);
    await page.getByPlaceholder('Confirm Password').click();
    await page.getByPlaceholder('Confirm Password').fill(credentials.password);
    await page.locator('#main form div').filter({ hasText: 'Subscribe to newsletter' }).locator('label').first().click();
    await page.getByRole('button', { name: 'Register' }).click();

    await expect(page.getByText('Account created successfully, an e-mail has been sent for verification.').first()).toBeVisible();

    return credentials;
}

test('register', async ({ page }) => {
    await register(page);
});

test('login', async ({ page }) => {
    const credentials = await register(page);

    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill(credentials.email);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(credentials.password);
    await page.getByRole('button', { name: 'Sign In' }).click();

    await page.getByLabel('Profile').click();
    await expect(page.getByText('Logout').first()).toBeVisible();
});

test('logout', async ({ page }) => {
    const credentials = await register(page);

    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill(credentials.email);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(credentials.password);
    await page.getByRole('button', { name: 'Sign In' }).click();
    await page.waitForTimeout(5000);

    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Logout' }).click();
    await page.waitForTimeout(5000);

    await page.getByLabel('Profile').click();
    await expect(page.getByText('Welcome Guest').first()).toBeVisible();
});
