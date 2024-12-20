import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Search by query', async ({page}) => {
  await page.goto(`${config.baseUrl}`);
  await page.getByLabel('Search products here').click();
  await page.getByLabel('Search products here').fill('arct');
  await page.getByLabel('Search products here').press('Enter');
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Search by image', async ({page}) => {
  await page.goto(`${config.baseUrl}`);
  await page.getByRole('search').getByLabel('Search', { exact: true }).click();
//   await page.getByText('Skip to main content $ USD $').setInputFiles('screenshot_1734499887147.png');
  await page.getByText('ballpoint').first().click();
  await page.getByText('Biro').first().click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
