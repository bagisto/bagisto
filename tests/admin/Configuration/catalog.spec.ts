import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Products of Catalog', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Products Product view page,' }).click();
    await page.getByLabel('Search Engine').selectOption('elastic');
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').first().click();
    await page.locator('label > div').first().click();
    await page.getByLabel('Compare options').click();
    await page.locator('div').filter({ hasText: 'Settings Settings refer to' }).nth(3).click();
    await page.locator('label > div').first().click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Image Search Option').click();
    await page.locator('div').filter({ hasText: 'Settings Settings refer to' }).nth(3).click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Search Engine').selectOption('database');
    await page.getByText('Search Engine DatabaseElastic SearchAdmin Search Mode DatabaseElastic').click();
    await page.getByLabel('Storefront Search Mode').selectOption('elastic');
    await page.getByLabel('Minimum query length').click();
    await page.getByLabel('Minimum query length').press('Tab');
    await page.getByLabel('Maximum query length').click();
    await page.getByLabel('Maximum query length').click();
    await page.getByLabel('Minimum query length').click();
    await page.getByLabel('Minimum query length').fill('211');
    await page.getByLabel('Maximum query length').click();
    await page.getByLabel('Maximum query length').fill('100');
    await page.getByLabel('Allowed number of Related').click();
    await page.getByLabel('Allowed number of Related').fill('21');
    await page.getByLabel('Allowed number of Up-Sell').click();
    await page.getByLabel('Allowed number of Up-Sell').fill('122');
    await page.getByLabel('Allowed number of Cross-Sell').click();
    await page.getByLabel('Allowed number of Cross-Sell').fill('1');
    await page.getByLabel('Default List Mode Default').selectOption('grid');
    await page.getByLabel('Products Per Page Default').click();
    await page.getByLabel('Products Per Page Default').click();
    await page.getByLabel('Products Per Page Default').fill('1222');
    await page.getByLabel('Sort By Default').selectOption('name-desc');
    await page.locator('div:nth-child(10) > div:nth-child(8) > .mb-4').click();
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').first().click();
    await page.locator('[id="catalog\\[products\\]\\[cache_small_image\\]\\[width\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_small_image\\]\\[width\\]"]').fill('12');
    await page.locator('[id="catalog\\[products\\]\\[cache_small_image\\]\\[height\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_small_image\\]\\[height\\]"]').fill('21');
    await page.getByLabel('Small Image Placeholder').click();
    // await page.getByLabel('Small Image Placeholder').setInputFiles('screenshot_1732536834544.png');
    await page.locator('[id="catalog\\[products\\]\\[cache_medium_image\\]\\[width\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_medium_image\\]\\[width\\]"]').fill('21');
    await page.locator('[id="catalog\\[products\\]\\[cache_medium_image\\]\\[height\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_medium_image\\]\\[height\\]"]').fill('22222221');
    await page.getByLabel('Medium Image Placeholder').click();
    // await page.getByLabel('Medium Image Placeholder').setInputFiles('screenshot_1732533139793.png');
    await page.locator('[id="catalog\\[products\\]\\[cache_large_image\\]\\[width\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_large_image\\]\\[width\\]"]').fill('12');
    await page.locator('[id="catalog\\[products\\]\\[cache_large_image\\]\\[height\\]"]').click();
    await page.locator('[id="catalog\\[products\\]\\[cache_large_image\\]\\[height\\]"]').fill('21');
    await page.locator('div:nth-child(18) > div > .mb-4').first().click();
    await page.locator('div:nth-child(18) > div > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Allowed Image Upload Size (in').click();
    await page.getByLabel('Allowed Image Upload Size (in').fill('12');
    await page.getByLabel('Allowed File Upload Size (in').click();
    await page.getByLabel('Allowed File Upload Size (in').fill('222');
    await page.locator('div:nth-child(22) > div > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Enable Social Share?').click();
    await page.locator('div:nth-child(22) > div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(22) > div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(22) > div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.getByText('Enable Share in What\'s App? What\'s App share link just will appear to mobile').click();
    await page.locator('div:nth-child(12) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(10) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(14) > .mb-4 > .relative > div').click();
    await page.getByLabel('Share Message').click();
    await page.getByLabel('Share Message').fill('Demo_qwsqc');
    await page.getByText('Products Back Save Configuration Default Default English Arabic Bengali').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Rich Snippets of Catalog', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Rich Snippets Set products' }).click();
    await page.locator('label > div').first().click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Show SKU').click();
    await page.locator('div:nth-child(12) > .mb-4 > .relative').click();
    await page.locator('div:nth-child(14) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(16) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first().click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Inventory of Catalog', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Inventory Configure inventory' }).click();
    await page.locator('label > div').click();
    await page.getByLabel('Out-of-Stock Threshold').click();
    await page.getByLabel('Out-of-Stock Threshold').fill('01');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
