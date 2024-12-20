import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Currency', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Currencies' }).click();
    await page.getByRole('button', { name: 'Create Currency' }).click();
    await page.locator('form').filter({ hasText: 'Create New Currency Code Name' }).locator('span').click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('sassss');
    await page.getByPlaceholder('Symbol').click();
    await page.getByPlaceholder('Symbol').fill('#');
    await page.getByPlaceholder('Decimal', { exact: true }).click();
    await page.getByPlaceholder('Decimal', { exact: true }).fill('123.34');
    await page.getByPlaceholder('Group Separator').click();
    await page.getByPlaceholder('Group Separator').fill('Demo_ert');
    await page.getByPlaceholder('Decimal Separator').click();
    await page.getByPlaceholder('Decimal Separator').fill('Demo_erter');
    await page.locator('select[name="currency_position"]').selectOption('left_with_space');
    await page.getByRole('button', { name: 'Save Currency' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Currency', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Currencies' }).click();
    await page.locator('div').filter({ hasText: /^66sassssASS$/ }).locator('a').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_sassss');
    await page.getByPlaceholder('Symbol').click();
    await page.getByPlaceholder('Symbol').fill('#');
    await page.getByPlaceholder('Decimal', { exact: true }).click();
    await page.getByPlaceholder('Decimal', { exact: true }).fill('123.34');
    await page.getByPlaceholder('Group Separator').click();
    await page.getByPlaceholder('Group Separator').fill('Demo_ert');
    await page.getByPlaceholder('Decimal Separator').click();
    await page.getByPlaceholder('Decimal Separator').fill('Demo_erter');
    await page.locator('select[name="currency_position"]').selectOption('left_with_space');
    await page.getByRole('button', { name: 'Save Currency' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Currency', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Currencies' }).click();
    await page.locator('div').filter({ hasText: /^66sassssASS$/ }).locator('a').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
