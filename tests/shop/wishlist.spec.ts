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

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.icon-cancel');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
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

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.icon-cancel');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});
