import { test, expect, config } from '../utils/setup';

test('Add To Wishlist', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
    await page.getByPlaceholder('email@example.com').press('Tab');
    await page.getByPlaceholder('Password').fill('testUser@123');
    await page.getByRole('button', { name: 'Sign In' }).click();
    await page.locator('.action-items > span').first().click();
    await page.locator('div:nth-child(9) > div:nth-child(2) > div > .-mt-9 > .action-items > span').first().click();

    await expect(page.getByText('Item Successfully Added To Wishlist').first()).toBeVisible();
});

test('Remove from Wishlist', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Profile').click();
    await page.getByRole('link', { name: 'Sign In' }).click();
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
    await page.getByPlaceholder('email@example.com').press('Tab');
    await page.getByPlaceholder('Password').fill('testUser@123');
    await page.getByRole('button', { name: 'Sign In' }).click();
    await page.locator('.action-items > span').first().click();
    await page.locator('div:nth-child(9) > div:nth-child(2) > div > .-mt-9 > .action-items > span').first().click();

    await expect(page.getByText('Item Successfully Removed From Wishlist').first()).toBeVisible();
});
