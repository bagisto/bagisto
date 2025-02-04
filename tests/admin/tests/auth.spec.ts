import { test, expect, config } from '../utils/setup';

test('Login', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();

    await expect(page.getByPlaceholder('Mega Search').first()).toBeVisible();
});

test('Logout', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('button', { name: 'E' }).click();
    await page.getByRole('link', { name: 'Logout' }).click();
    await page.waitForTimeout(5000);

    await expect(page.getByPlaceholder('Password').first()).toBeVisible();
});
