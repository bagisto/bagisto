import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Inventory Sources', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Inventory Sources' }).click();
    await page.getByRole('link', { name: 'Create Inventory Source' }).click();
    await page.getByPlaceholder('Code', { exact: true }).click();
    await page.getByPlaceholder('Code', { exact: true }).fill('sdsdsds');
    await page.locator('#name').click();
    await page.locator('#name').fill('Demo_sdf');
    await page.locator('#name').click();
    await page.locator('#name').fill('Demo_sdfsdfsdfwe');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_fwewefsdd');
    await page.locator('#contact_name').click();
    await page.locator('#contact_name').fill('Demo_sdfserw');
    await page.getByPlaceholder('Email').click();
    await page.getByPlaceholder('Email').fill('Demo_sfdfweer@sf.fgd');
    await page.getByPlaceholder('Contact Number').click();
    await page.getByPlaceholder('Contact Number').fill('9876543323');
    await page.getByPlaceholder('Fax').click();
    await page.getByPlaceholder('Fax').fill('3235746');
    await page.locator('#country').selectOption('AZ');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_tyutyu');
    await page.getByPlaceholder('City').click();
    await page.getByPlaceholder('City').fill('Demo_yturtyu');
    await page.getByPlaceholder('Street').click();
    await page.getByPlaceholder('Street').fill('Demo_5t6y456');
    await page.getByPlaceholder('Postcode').click();
    await page.getByPlaceholder('Postcode').fill('4565677');
    await page.locator('.relative > label').click();
    await page.getByPlaceholder('Latitude').click();
    await page.getByPlaceholder('Latitude').fill('45');
    await page.getByPlaceholder('Longitude').click();
    await page.getByPlaceholder('Longitude').fill('54');
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('45');
    await page.getByRole('button', { name: 'Save Inventory Sources' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Inventory Sources', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Inventory Sources' }).click();
    await page.locator('div').filter({ hasText: /^2sdsdsdsdsdfsdfsdfwe45Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Code', { exact: true }).click();
    await page.getByPlaceholder('Code', { exact: true }).fill('sdsdsds');
    await page.locator('#name').click();
    await page.locator('#name').fill('Demo_sdf');
    await page.locator('#name').click();
    await page.locator('#name').fill('Demo_sdfsdfsdfwe');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_fwewefsdd');
    await page.locator('#contact_name').click();
    await page.locator('#contact_name').fill('Demo_sdfserw');
    await page.getByPlaceholder('Email').click();
    await page.getByPlaceholder('Email').fill('Demo_sfdfweer@sf.fgd');
    await page.getByPlaceholder('Contact Number').click();
    await page.getByPlaceholder('Contact Number').fill('9876543323');
    await page.getByPlaceholder('Fax').click();
    await page.getByPlaceholder('Fax').fill('3235746');
    await page.locator('#country').selectOption('AZ');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_tyutyu');
    await page.getByPlaceholder('City').click();
    await page.getByPlaceholder('City').fill('Demo_yturtyu');
    await page.getByPlaceholder('Street').click();
    await page.getByPlaceholder('Street').fill('Demo_5t6y456');
    await page.getByPlaceholder('Postcode').click();
    await page.getByPlaceholder('Postcode').fill('4565677');
    await page.locator('.relative > label').click();
    await page.getByPlaceholder('Latitude').click();
    await page.getByPlaceholder('Latitude').fill('45');
    await page.getByPlaceholder('Longitude').click();
    await page.getByPlaceholder('Longitude').fill('54');
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('45');
    await page.getByRole('button', { name: 'Save Inventory Sources' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Inventory Sources', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Inventory Sources' }).click();
    await page.locator('div').filter({ hasText: /^2sdsdsdsdsdfsdfsdfwe45Active$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
