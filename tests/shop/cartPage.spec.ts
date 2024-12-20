import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Increment', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
  await page.getByText('Arctic Touchscreen Winter Gloves $21.00 Add To Cart').first().click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
  await page.goto(`${config.baseUrl}/checkout/cart`);
  await page.getByLabel('Increase Quantity').first().click();
  await page.locator('div').filter({ hasText: /^2$/ }).getByLabel('Increase Quantity').click();
  await page.locator('div').filter({ hasText: /^3$/ }).getByLabel('Increase Quantity').click();
  await page.getByLabel('Increase Quantity').nth(1).click();
  await page.locator('div').filter({ hasText: /^2$/ }).getByLabel('Increase Quantity').click();
  await page.locator('div').filter({ hasText: /^1$/ }).getByLabel('Increase Quantity').click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Decrement', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
  await page.goto(`${config.baseUrl}/checkout/cart`);
  await page.getByLabel('Increase Quantity').first().click();
  await page.locator('div').filter({ hasText: /^2$/ }).getByLabel('Increase Quantity').click();
  await page.locator('div').filter({ hasText: /^3$/ }).getByLabel('Increase Quantity').click();
  await page.locator('div').filter({ hasText: /^4$/ }).getByLabel('Increase Quantity').click();
  await page.locator('div').filter({ hasText: /^5$/ }).getByLabel('Decrease Quantity').click();
  await page.locator('div').filter({ hasText: /^4$/ }).getByLabel('Decrease Quantity').click();
  await page.getByRole('button', { name: 'Update Cart' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Remove One', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
  await page.goto(`${config.baseUrl}/checkout/cart`);
  await page.getByRole('button', { name: 'Remove' }).first().click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Remove All', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(1).click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').nth(2).click();
  await page.goto(`${config.baseUrl}/checkout/cart`);
  await page.locator('.icon-uncheck').first().click();
  await page.getByRole('button', { name: 'Remove' }).first().click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Apply Coupon', async ({ page }) => {
  await page.goto(`${config.baseUrl}`);
  await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();
  await page.goto(`${config.baseUrl}/checkout/cart`);
  await page.getByRole('button', { name: 'Apply Coupon' }).click();
  await page.getByPlaceholder('Enter your code').click();
  await page.getByPlaceholder('Enter your code').fill('12345');
  await page.getByRole('button', { name: 'Apply', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
