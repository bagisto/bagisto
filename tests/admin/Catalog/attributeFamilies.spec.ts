import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

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
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
