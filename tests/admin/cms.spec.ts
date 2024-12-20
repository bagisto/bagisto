import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Create Page', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' CMS' }).click();
  await page.getByRole('link', { name: 'Create Page' }).click();
  await page.getByPlaceholder('Title', { exact: true }).click();
  await page.getByPlaceholder('Title', { exact: true }).fill('Demo_Title');
  await page.locator('#channels_1').nth(1).click();
  await page.locator('iframe[title="Rich Text Area"]').contentFrame().locator('html').click();
  await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').fill('Demo_sdkjfuhgyu hswdfgcwae');
  await page.getByPlaceholder('Meta Title').click();
  await page.getByPlaceholder('Meta Title').fill('Demo_wedwedwd');
  await page.getByPlaceholder('URL Key').click();
  await page.getByPlaceholder('URL Key').fill('Demo_ewdwqedqe4de');
  await page.getByPlaceholder('Meta Keywords').click();
  await page.getByPlaceholder('Meta Keywords').fill('Demo_qw3edxcwe');
  await page.getByPlaceholder('Meta Description').click();
  await page.getByPlaceholder('Meta Description').fill('Demo_ew3r4434');
  await page.getByRole('button', { name: 'Save Page' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Page', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' CMS' }).click();
  await page.locator('div').filter({ hasText: /^11okhjasdewdwqedqe4ded$/ }).locator('span').nth(2).click();
  await page.getByPlaceholder('Page Title').click();
  await page.getByPlaceholder('Title', { exact: true }).fill('Demo_okhjasd');
  await page.locator('#channels_1').nth(1).click();
  await page.locator('iframe[title="Rich Text Area"]').contentFrame().locator('html').click();
  await page.locator('iframe[title="Rich Text Area"]').contentFrame().getByLabel('Rich Text Area. Press ALT-0').fill('Demo_sdkjfuhgyu hswdfgcwae');
  await page.getByPlaceholder('Meta Title').click();
  await page.getByPlaceholder('Meta Title').fill('Demo_wedwedwd');
  await page.getByPlaceholder('URL Key').click();
  await page.getByPlaceholder('URL Key').fill('Demo_ewdwqedqe4de');
  await page.getByPlaceholder('Meta Keywords').click();
  await page.getByPlaceholder('Meta Keywords').fill('Demo_qw3edxcwe');
  await page.getByPlaceholder('Meta Description').click();
  await page.getByPlaceholder('Meta Description').fill('Demo_ew3r4434');
  await page.getByRole('button', { name: 'Save Page' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Page', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' CMS' }).click();
  await page.locator('div').filter({ hasText: /^11okhjasdewdwqedqe4ded$/ }).locator('span').nth(3).click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Mass Delete Pages', async ({page}) => {
  await page.goto(`${config.baseUrl}/admin/login`);
  await page.getByPlaceholder('Email Address').click();
  await page.getByPlaceholder('Email Address').fill(config.adminEmail);
  await page.getByPlaceholder('Password').click();
  await page.getByPlaceholder('Password').fill(config.adminPassword);
  await page.getByLabel('Sign In').click();
  await page.getByRole('link', { name: ' CMS' }).click();
  await page.locator('div').filter({ hasText: /^2Return Policyreturn-policy$/ }).locator('label span').click();
  await page.locator('div').filter({ hasText: /^4Terms & Conditionsterms-conditions$/ }).locator('label span').click();
  await page.locator('div').filter({ hasText: /^1About Usabout-us$/ }).locator('label span').click();
  await page.getByRole('button', { name: 'Select Action ' }).click();
  await page.getByRole('link', { name: 'Delete' }).click();
  await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
