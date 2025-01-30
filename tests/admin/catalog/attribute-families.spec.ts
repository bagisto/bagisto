import { test, expect, config } from '../../utils/setup';

test('Delete Attribute Family', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Catalog' }).click();
    await page.getByRole('link', { name: 'Attribute Families' }).click();
    await page.locator('div').filter({ hasText: /^1defaultDefault$/ }).locator('span').nth(1).click();
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
