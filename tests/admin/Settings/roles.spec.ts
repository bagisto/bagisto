import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Role', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Roles' }).click();
    await page.getByRole('link', { name: 'Create Role' }).click();
    await page.locator('label').filter({ hasText: 'Dashboard' }).locator('span').click();
    await page.locator('div').filter({ hasText: /^OrdersCreateViewCancel$/ }).locator('span').nth(1).click();
    await page.locator('label').filter({ hasText: 'Cancel' }).locator('span').click();
    await page.locator('div').filter({ hasText: /^InvoicesViewCreate$/ }).locator('label').nth(2).click();
    await page.locator('label').filter({ hasText: /^View$/ }).nth(2).click();
    await page.locator('div').filter({ hasText: /^CategoriesCreateEditDelete$/ }).locator('span').nth(1).click();
    await page.locator('div').filter({ hasText: /^AddressesCreateEditDelete$/ }).locator('span').nth(2).click();
    await page.locator('div').filter({ hasText: /^Catalog RulesCreateEditDelete$/ }).locator('span').nth(1).click();
    await page.locator('div').filter({ hasText: /^Cart RulesCreateCopyEditDelete$/ }).locator('span').nth(3).click();
    await page.locator('div').filter({ hasText: /^URL RewritesCreateEditDelete$/ }).locator('span').nth(2).click();
    await page.locator('.v-tree-item-wrapper > div:nth-child(7) > div:nth-child(6) > .group > div').click();
    await page.locator('div').filter({ hasText: /^Inventory SourcesCreateEditDelete$/ }).locator('label').nth(2).click();
    await page.locator('div').filter({ hasText: /^ThemesCreateEditDelete$/ }).locator('label').nth(3).click();
    await page.locator('div').filter({ hasText: /^Tax CategoriesCreateEditDelete$/ }).locator('label').nth(3).click();
    await page.locator('div').filter({ hasText: /^ImportsCreateEditDeleteImport$/ }).locator('label').nth(3).click();
    await page.locator('label').filter({ hasText: 'Dashboard' }).locator('div').click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('test');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sdfsdfsdsd dsf');
    await page.getByRole('button', { name: 'Save Role' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Role', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Roles' }).click();
    await page.getByRole('link', { name: 'Create Role' }).click();
    await page.locator('div').filter({ hasText: /^2Usercustom$/ }).locator('span').first().click();
    await page.locator('label').filter({ hasText: 'Dashboard' }).locator('span').click();
    await page.locator('div').filter({ hasText: /^OrdersCreateViewCancel$/ }).locator('span').nth(1).click();
    await page.locator('label').filter({ hasText: 'Cancel' }).locator('span').click();
    await page.locator('div').filter({ hasText: /^InvoicesViewCreate$/ }).locator('label').nth(2).click();
    await page.locator('label').filter({ hasText: /^View$/ }).nth(2).click();
    await page.locator('div').filter({ hasText: /^CategoriesCreateEditDelete$/ }).locator('span').nth(1).click();
    await page.locator('div').filter({ hasText: /^AddressesCreateEditDelete$/ }).locator('span').nth(2).click();
    await page.locator('div').filter({ hasText: /^Catalog RulesCreateEditDelete$/ }).locator('span').nth(1).click();
    await page.locator('div').filter({ hasText: /^Cart RulesCreateCopyEditDelete$/ }).locator('span').nth(3).click();
    await page.locator('div').filter({ hasText: /^URL RewritesCreateEditDelete$/ }).locator('span').nth(2).click();
    await page.locator('.v-tree-item-wrapper > div:nth-child(7) > div:nth-child(6) > .group > div').click();
    await page.locator('div').filter({ hasText: /^Inventory SourcesCreateEditDelete$/ }).locator('label').nth(2).click();
    await page.locator('div').filter({ hasText: /^ThemesCreateEditDelete$/ }).locator('label').nth(3).click();
    await page.locator('div').filter({ hasText: /^Tax CategoriesCreateEditDelete$/ }).locator('label').nth(3).click();
    await page.locator('div').filter({ hasText: /^ImportsCreateEditDeleteImport$/ }).locator('label').nth(3).click();
    await page.locator('label').filter({ hasText: 'Dashboard' }).locator('div').click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('test');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sdfsdfsdsd dsf');
    await page.getByRole('button', { name: 'Save Role' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Role', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Roles' }).click();
    await page.getByRole('link', { name: 'Create Role' }).click();
    await page.locator('div').filter({ hasText: /^2Usercustom$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
