import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Address of Customer', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Address Set country, state,' }).click();
    await page.getByLabel('Lines in a Street Address').click();
    await page.getByLabel('Lines in a Street Address').fill('2');
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.getByLabel('State Default').click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.locator('div').filter({ hasText: /^Country Default$/ }).nth(1).click();
    await page.locator('label > div').first().click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Captcha of Customer', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Captcha Set site key, secret' }).click();
    await page.getByLabel('Site Key Default').click();
    await page.getByLabel('Site Key Default').fill('asded@werwe/tyuyhg');
    await page.getByLabel('Secret Key Default').click();
    await page.getByLabel('Secret Key Default').fill('Demo_tyutyu yut');
    await page.locator('label > div').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Settings of Customer', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();

    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Settings Set newsletter' }).click();
    await page.locator('label > div').first().click();
    await page.getByLabel('Redirect Customer to the').selectOption('account');
    await page.getByLabel('Default Group').selectOption('wholesale');
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').first().click();
    await page.locator('div').filter({ hasText: /^Enable Facebook Default$/ }).nth(1).click();
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(12) > div > .mb-4 > .relative > div').first().click();
    await page.locator('label > div').first().click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
