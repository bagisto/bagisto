import { test, expect, config } from '../utils/setup';

test('Add to cart', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
