import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Review Product', async ({page}) => {
  await page.goto(`${config.baseUrl}`);
  await page.getByLabel('Profile').click();
  await page.getByRole('link', { name: 'Sign In' }).click();
  await page.getByPlaceholder('email@example.com').click();
  await page.getByPlaceholder('email@example.com').fill('testUser@gmail.com');
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill('testUser@123');
  await page.getByRole('button', { name: 'Sign In' }).click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).getByLabel('Arctic Touchscreen Winter').click();
  await page.getByRole('button', { name: 'Reviews' }).click();
  await page.locator('#review-tab').getByText('Write a Review').click();
  await page.locator('#review-tab span').nth(3).click();
  await page.locator('#review-tab span').nth(4).click();
  await page.getByPlaceholder('Title').click();
  await page.getByPlaceholder('Title').fill('My Review');
  await page.getByPlaceholder('Comment').click();
  await page.getByPlaceholder('Comment').fill('Great Product');
  await page.getByRole('button', { name: 'Submit Review' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
