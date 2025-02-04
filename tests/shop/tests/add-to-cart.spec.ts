import { test, expect, config } from '../utils/setup';

test('Add to cart', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();

    await expect(page.getByText('Item Added Successfully').first()).toBeVisible();
});
