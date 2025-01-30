import { test, expect, config } from '../utils/setup';

test('Search by query', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.getByLabel('Search products here').click();
    await page.getByLabel('Search products here').fill('arct');
    await page.getByLabel('Search products here').press('Enter');

    await page.waitForSelector('text=Search results for : arct', { timeout: 5000 });

});
