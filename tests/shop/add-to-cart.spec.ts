import { test, expect, config } from '../utils/setup';

test('Add to cart', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();

    try {
        await page.waitForSelector('.icon-toast-done', { timeout: 5000 });

        const message = await page.$eval('.icon-toast-done', el => el.parentNode.innerText);
        await page.click('.cursor-pointer.icon-cancel');

        console.log(message);
    } catch(e) {
        console.log(page.url());
    }
});
