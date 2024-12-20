import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Locale', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('button', { name: 'Create Locale' }).click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('we');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_web');
    await page.locator('#direction').selectOption('rtl');
    await page.getByRole('button', { name: 'Save Locale' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Locale', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.locator('div').filter({ hasText: /^20wewebRTL$/ }).locator('a').first().click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('we');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_web');
    await page.locator('#direction').selectOption('rtl');
    await page.getByRole('button', { name: 'Save Locale' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Locale', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.locator('div').filter({ hasText: /^20wewebRTL$/ }).locator('a').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
