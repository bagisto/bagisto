import { test, expect, config } from '../setup';

test('should add product to cart', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);

    await page.locator('#main div')
        .filter({ hasText: 'New Products View All New' })
        .locator('button')
        .first()
        .waitFor({ state: 'visible' });

    await page.locator('#main div')
        .filter({ hasText: 'New Products View All New' })
        .locator('button')
        .first()
        .click();

    await expect(page.getByText('Item Added Successfully').first()).toBeVisible();
});
