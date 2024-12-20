import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Login', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Logout', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('button', { name: 'E' }).click();
  await page.getByRole('link', { name: 'Logout' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
