import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Channel', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Channels' }).click();
    await page.getByRole('link', { name: 'Create Channel' }).click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('sdsdfwe');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_dfsfwe');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_dfsfwe wewe');
    await page.getByPlaceholder('Description', { exact: true }).click();
    await page.getByPlaceholder('Description', { exact: true }).fill('Demo_sdfwe ewrwew');
    await page.locator('#inventory_sources_1').nth(1).click();
    await page.locator('#root_category_id').selectOption('1');
    await page.getByPlaceholder('https://www.example.com (Don\'').click();
    await page.getByPlaceholder('https://www.example.com (Don\'').fill('Demo_dfsdeew');
    await page.getByPlaceholder('Meta title').click();
    await page.getByPlaceholder('Meta title').fill('Demo_werewrwe');
    await page.getByPlaceholder('Meta keywords').click();
    await page.getByPlaceholder('Meta keywords').fill('Demo_sdfser');
    await page.getByPlaceholder('Meta description').click();
    await page.getByPlaceholder('Meta description').fill('Demo_dsfwerwewe');
    await page.getByPlaceholder('Message').click();
    await page.getByPlaceholder('Message').fill('Demo_werwewe');
    await page.getByPlaceholder('Allowed IPs').click();
    await page.getByPlaceholder('Allowed IPs').fill('Demo_rwe');
    await page.locator('.relative > label').click();
    await page.locator('#currencies_64').nth(1).click();
    await page.locator('#currencies_62').nth(1).click();
    await page.getByLabel('CFA Franc BEAC').click();
    await page.locator('#base_currency_id').selectOption('18');
    await page.locator('label').filter({ hasText: 'Russian Ruble' }).click();
    await page.getByText('Locales Arabic Bengali').click();
    await page.locator('label').filter({ hasText: 'Sinhala' }).click();
    await page.getByText('Turkish').first().click();
    await page.locator('#default_locale_id').selectOption('17');
    await page.locator('label').filter({ hasText: 'South African Rand' }).click();
    await page.locator('label').filter({ hasText: 'Zambian Kwacha' }).click();
    await page.locator('label').filter({ hasText: 'CFA Franc BEAC' }).click();
    await page.locator('label').filter({ hasText: 'Fijian Dollar' }).click();
    await page.locator('#inventory_sources_1').nth(1).click();
    await page.getByText('Ukrainian').first().click();
    await page.getByLabel('Ukrainian', { exact: true }).click();
    await page.getByText('Turkish').first().click();
    await page.getByRole('button', { name: 'Save Channel' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Channel', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Channels' }).click();
    await page.locator('div').filter({ hasText: /^2sdsdfwedfsfwe wewedfsdeew$/ }).locator('span').first().click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('sdsdfwe');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_dfsfwe');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_dfsfwe wewe');
    await page.getByPlaceholder('Description', { exact: true }).click();
    await page.getByPlaceholder('Description', { exact: true }).fill('Demo_sdfwe ewrwew');
    await page.locator('#inventory_sources_1').nth(1).click();
    await page.locator('#root_category_id').selectOption('1');
    await page.getByPlaceholder('https://www.example.com (Don\'').click();
    await page.getByPlaceholder('https://www.example.com (Don\'').fill('Demo_dfsdeew');
    await page.getByPlaceholder('Meta title').click();
    await page.getByPlaceholder('Meta title').fill('Demo_werewrwe');
    await page.getByPlaceholder('Meta keywords').click();
    await page.getByPlaceholder('Meta keywords').fill('Demo_sdfser');
    await page.getByPlaceholder('Meta description').click();
    await page.getByPlaceholder('Meta description').fill('Demo_dsfwerwewe');
    await page.getByPlaceholder('Message').click();
    await page.getByPlaceholder('Message').fill('Demo_werwewe');
    await page.getByPlaceholder('Allowed IPs').click();
    await page.getByPlaceholder('Allowed IPs').fill('Demo_rwe');
    await page.locator('.relative > label').click();
    await page.locator('#currencies_64').nth(1).click();
    await page.locator('#currencies_62').nth(1).click();
    await page.getByLabel('CFA Franc BEAC').click();
    await page.locator('#base_currency_id').selectOption('18');
    await page.locator('label').filter({ hasText: 'Russian Ruble' }).click();
    await page.getByText('Locales Arabic Bengali').click();
    await page.locator('label').filter({ hasText: 'Sinhala' }).click();
    await page.getByText('Turkish').first().click();
    await page.locator('#default_locale_id').selectOption('17');
    await page.locator('label').filter({ hasText: 'South African Rand' }).click();
    await page.locator('label').filter({ hasText: 'Zambian Kwacha' }).click();
    await page.locator('label').filter({ hasText: 'CFA Franc BEAC' }).click();
    await page.locator('label').filter({ hasText: 'Fijian Dollar' }).click();
    await page.locator('#inventory_sources_1').nth(1).click();
    await page.getByText('Ukrainian').first().click();
    await page.getByLabel('Ukrainian', { exact: true }).click();
    await page.getByText('Turkish').first().click();
    await page.getByRole('button', { name: 'Save Channel' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Channel', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Channels' }).click();
    await page.locator('div').filter({ hasText: /^2sdsdfwedfsfwe wewedfsdeew$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
