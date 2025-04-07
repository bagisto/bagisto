import { test, expect, config } from '../setup';
import { register } from '../utils/customer';

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

    await page.getByLabel('Profile').waitFor({ state: 'visible' });
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Logout' }).click();

    await page.getByLabel('Profile').waitFor({ state: 'visible' });
    await page.getByLabel('Profile').click();
    await expect(page.getByText('Welcome Guest').first()).toBeVisible();
});
