import { test, expect, config } from '../utils/setup';

test('Increment', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
    await page.getByText('Arctic Touchscreen Winter Gloves $21.00 Add To Cart').first().click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
    await page.waitForTimeout(5000);
    await page.goto(`${config.baseUrl}/checkout/cart`);
    await page.getByLabel('Increase Quantity').first().click();
    await page.getByRole('button', { name: 'Update Cart' }).click();

    await page.waitForSelector('text=Quantity updated successfully');
});

test('Decrement', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
    await page.waitForTimeout(5000);
    await page.goto(`${config.baseUrl}/checkout/cart`);
    await page.getByLabel('Increase Quantity').first().click();
    await page.getByLabel('Decrease Quantity').click();
    await page.getByRole('button', { name: 'Update Cart' }).click();

    await page.waitForSelector('text=Quantity updated successfully');
});

test('Remove One', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
    await page.waitForTimeout(5000);
    await page.goto(`${config.baseUrl}/checkout/cart`);
    await page.getByRole('button', { name: 'Remove' }).first().click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await page.waitForSelector('text=Item is successfully removed from the cart.');
});

test('Remove All', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
    await page.waitForTimeout(5000);
    await page.goto(`${config.baseUrl}/checkout/cart`);
    await page.locator('.icon-uncheck').first().click();
    await page.getByRole('button', { name: 'Remove' }).first().click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await page.waitForSelector('text=Selected items successfully removed from cart.');
});

test('Apply Coupon', async ({ page }) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
    await page.waitForTimeout(5000);
    await page.goto(`${config.baseUrl}/checkout/cart`);
    await page.getByRole('button', { name: 'Apply Coupon' }).click();
    await page.getByPlaceholder('Enter your code').click();
    await page.getByPlaceholder('Enter your code').fill('12345');
    await page.getByRole('button', { name: 'Apply', exact: true }).click();

    await page.waitForSelector('text=Coupon code applied successfully.');
});
