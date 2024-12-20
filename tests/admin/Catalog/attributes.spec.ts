import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Attribute', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' Catalog' }).click();
  await page.getByRole('link', { name: 'Attributes' }).click();
  await page.getByRole('link', { name: 'Create Attributes' }).click();
  await page.getByPlaceholder('Admin').click();
  await page.getByPlaceholder('Admin').fill('Demo_sfdatsdf');
  await page.getByPlaceholder('Admin').press('Tab');
  await page.getByPlaceholder('Arabic').fill('Demo_ysdaguwy');
  await page.getByPlaceholder('Arabic').press('Tab');
  await page.getByPlaceholder('Bengali').fill('Demo_cgyusd');
  await page.getByPlaceholder('Bengali').press('Tab');
  await page.getByPlaceholder('Brazilian Portuguese').fill('Demo_hgscbsdy');
  await page.getByPlaceholder('Brazilian Portuguese').press('Tab');
  await page.getByPlaceholder('Chinese').fill('Demo_dsuyadtg');
  await page.getByPlaceholder('Chinese').press('Tab');
  await page.getByPlaceholder('Dutch').fill('Demo_uysdcfg');
  await page.getByPlaceholder('Dutch').press('Tab');
  await page.getByPlaceholder('English').fill('Demo_scyud');
  await page.getByPlaceholder('English').press('Tab');
  await page.getByPlaceholder('French').fill('Demo_ysdcgwsa');
  await page.getByPlaceholder('French').press('Tab');
  await page.getByPlaceholder('German').fill('Demo_iysd78');
  await page.getByPlaceholder('German').press('Tab');
  await page.getByPlaceholder('Hebrew').fill('Demo_hjsdgsyiud');
  await page.getByPlaceholder('Hebrew').press('Tab');
  await page.getByPlaceholder('Hindi').fill('Demo_hjsgdis');
  await page.getByPlaceholder('Hindi').press('Tab');
  await page.getByPlaceholder('Italian').fill('Demo_hsdgsd');
  await page.getByPlaceholder('Italian').press('Tab');
  await page.getByPlaceholder('Japanese').fill('Demo_iyusdcsd');
  await page.getByPlaceholder('Japanese').press('Tab');
  await page.getByPlaceholder('Persian').fill('Demo_ghsdgi');
  await page.getByPlaceholder('Persian').press('Tab');
  await page.getByPlaceholder('Polish').fill('Demo_bhjsdgiw');
  await page.getByPlaceholder('Polish').press('Tab');
  await page.getByPlaceholder('Russian').fill('Demo_hjsdgyiuws uhsd');
  await page.getByPlaceholder('Russian').press('Tab');
  await page.getByPlaceholder('Sinhala').fill('Demo_gc whisxg');
  await page.getByPlaceholder('Sinhala').press('Tab');
  await page.getByPlaceholder('Spanish').fill('Demo_bwisdcuyw ');
  await page.getByPlaceholder('Spanish').press('Tab');
  await page.getByPlaceholder('Turkish').fill('Demo_ciwswi  igwsi');
  await page.getByPlaceholder('Turkish').press('Tab');
  await page.getByPlaceholder('Ukrainian').fill('Demo_hbwieyh iuw');
  await page.getByPlaceholder('Ukrainian').press('Tab');
  await page.getByPlaceholder('Attribute Code').fill('Demo_dscbwi');
  await page.getByPlaceholder('Attribute Code').press('Tab');
  await page.locator('#type').selectOption('textarea');
  await page.locator('.relative > label').click();
  await page.locator('input[name="default_value"]').click();
  await page.locator('input[name="default_value"]').fill('Demo_wdfhwbe');
  await page.locator('#is_required').nth(1).click();
  await page.locator('#is_unique').nth(1).click();
  await page.locator('#value_per_locale').nth(1).click();
  await page.locator('#value_per_channel').nth(1).click();
  await page.getByLabel('Value Per Channel').click();
  await page.getByRole('button', { name: 'Save Attribute' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Attribute', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' Catalog' }).click();
  await page.getByRole('link', { name: 'Attributes' }).click();
  await page.locator('div').filter({ hasText: /^28manage_stockManage StockbooleanFalseFalseFalseTrue2024-12-17 17:01:15$/ }).locator('span').nth(1).click();
  await page.getByPlaceholder('Admin').click();
  await page.getByPlaceholder('Admin').fill('Demo_sfdatsdf');
  await page.getByPlaceholder('Admin').press('Tab');
  await page.getByPlaceholder('Arabic').fill('Demo_ysdaguwy');
  await page.getByPlaceholder('Arabic').press('Tab');
  await page.getByPlaceholder('Bengali').fill('Demo_cgyusd');
  await page.getByPlaceholder('Bengali').press('Tab');
  await page.getByPlaceholder('Brazilian Portuguese').fill('Demo_hgscbsdy');
  await page.getByPlaceholder('Brazilian Portuguese').press('Tab');
  await page.getByPlaceholder('Chinese').fill('Demo_dsuyadtg');
  await page.getByPlaceholder('Chinese').press('Tab');
  await page.getByPlaceholder('Dutch').fill('Demo_uysdcfg');
  await page.getByPlaceholder('Dutch').press('Tab');
  await page.getByPlaceholder('English').fill('Demo_scyud');
  await page.getByPlaceholder('English').press('Tab');
  await page.getByPlaceholder('French').fill('Demo_ysdcgwsa');
  await page.getByPlaceholder('French').press('Tab');
  await page.getByPlaceholder('German').fill('Demo_iysd78');
  await page.getByPlaceholder('German').press('Tab');
  await page.getByPlaceholder('Hebrew').fill('Demo_hjsdgsyiud');
  await page.getByPlaceholder('Hebrew').press('Tab');
  await page.getByPlaceholder('Hindi').fill('Demo_hjsgdis');
  await page.getByPlaceholder('Hindi').press('Tab');
  await page.getByPlaceholder('Italian').fill('Demo_hsdgsd');
  await page.getByPlaceholder('Italian').press('Tab');
  await page.getByPlaceholder('Japanese').fill('Demo_iyusdcsd');
  await page.getByPlaceholder('Japanese').press('Tab');
  await page.getByPlaceholder('Persian').fill('Demo_ghsdgi');
  await page.getByPlaceholder('Persian').press('Tab');
  await page.getByPlaceholder('Polish').fill('Demo_bhjsdgiw');
  await page.getByPlaceholder('Polish').press('Tab');
  await page.getByPlaceholder('Russian').fill('Demo_hjsdgyiuws uhsd');
  await page.getByPlaceholder('Russian').press('Tab');
  await page.getByPlaceholder('Sinhala').fill('Demo_gc whisxg');
  await page.getByPlaceholder('Sinhala').press('Tab');
  await page.getByPlaceholder('Spanish').fill('Demo_bwisdcuyw ');
  await page.getByPlaceholder('Spanish').press('Tab');
  await page.getByPlaceholder('Turkish').fill('Demo_ciwswi  igwsi');
  await page.getByPlaceholder('Turkish').press('Tab');
  await page.getByPlaceholder('Ukrainian').fill('Demo_hbwieyh iuw');
  await page.getByPlaceholder('Ukrainian').press('Tab');
  await page.getByPlaceholder('Attribute Code').fill('Demo_dscbwi');
  await page.getByPlaceholder('Attribute Code').press('Tab');
  await page.locator('#type').selectOption('textarea');
  await page.locator('.relative > label').click();
  await page.locator('input[name="default_value"]').click();
  await page.locator('input[name="default_value"]').fill('Demo_wdfhwbe');
  await page.locator('#is_required').nth(1).click();
  await page.locator('#is_unique').nth(1).click();
  await page.locator('#value_per_locale').nth(1).click();
  await page.locator('#value_per_channel').nth(1).click();
  await page.getByLabel('Value Per Channel').click();
  await page.getByRole('button', { name: 'Save Attribute' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Attribute', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' Catalog' }).click();
  await page.getByRole('link', { name: 'Attributes' }).click();
  await page.locator('div').filter({ hasText: /^24sizeSizeselectFalseFalseFalseFalse2024-12-17 17:01:15$/ }).locator('span').nth(2).click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Mass Delete Attributes', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' Catalog' }).click();
  await page.getByRole('link', { name: 'Attributes' }).click();
  await page.locator('div').filter({ hasText: /^25brandBrandselectFalseFalseFalseFalse2024-12-17 17:01:15$/ }).locator('label span').click();
  await page.locator('div').filter({ hasText: /^24sizeSizeselectFalseFalseFalseFalse2024-12-17 17:01:15$/ }).locator('label span').click();
  await page.locator('div').filter({ hasText: /^28manage_stockManage StockbooleanFalseFalseFalseFalse2024-12-17 17:01:15$/ }).locator('label span').click();
  await page.getByRole('button', { name: 'Select Action ' }).click();
  await page.getByRole('link', { name: ' Delete' }).click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
