import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Settings of Email', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Email Settings Set email' }).click();
    await page.getByLabel('Email Sender Name Default').click();
    await page.getByLabel('Email Sender Name Default').fill('User _test');
    await page.getByLabel('Shop Email Address Default').click();
    await page.getByLabel('Shop Email Address Default').fill('Demo_User@gmail.com');
    await page.getByLabel('Admin Name Default').click();
    await page.getByLabel('Admin Name Default').fill('Admin');
    await page.getByLabel('Admin Email Default').click();
    await page.getByLabel('Admin Email Default').fill('Admin@gmail.com');
    await page.getByLabel('Contact Name Default').click();
    await page.getByLabel('Contact Name Default').fill('Name');
    await page.getByLabel('Contact Email Default').click();
    await page.getByLabel('Contact Email Default').fill('Demo_User@example.com');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Notifications of Email', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();await page.getByText('Dashboard Sales Orders').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Notifications To configure,' }).click();
    await page.locator('.mb-4 > .mb-4').first().click();
    await page.locator('label > div').first().click();
    await page.locator('div:nth-child(4) > .mb-4').click();
    await page.locator('div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(12) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(20) > .mb-4 > .relative > div').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
