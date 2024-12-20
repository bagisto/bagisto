import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Tax Rate', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.getByRole('link', { name: 'Tax Rates' }).click();
    await page.getByRole('link', { name: 'Create Tax Rate' }).click();
    await page.locator('select[name="country"]').selectOption('AI');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_asdweas');
    await page.getByPlaceholder('Rate').click();
    await page.getByPlaceholder('Rate').fill('32');
    await page.getByPlaceholder('Identifier').click();
    await page.getByPlaceholder('Identifier').fill('Demo_sasd');
    await page.locator('.relative > label').click();
    await page.getByText('Zip From Zip To').click();
    await page.getByPlaceholder('Zip From').click();
    await page.getByPlaceholder('Zip From').fill('234234');
    await page.getByPlaceholder('Zip To').click();
    await page.getByPlaceholder('Zip To').fill('2344234');
    await page.getByRole('button', { name: 'Save Tax Rate' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Tax Rate', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.getByRole('link', { name: 'Tax Rates' }).click();
    await page.locator('div').filter({ hasText: /^1sasdasdweasAI2342344234423432\.0000$/ }).locator('span').first().click();
    await page.locator('select[name="country"]').selectOption('AI');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_asdweas');
    await page.getByPlaceholder('Rate').click();
    await page.getByPlaceholder('Rate').fill('32');
    await page.getByPlaceholder('Identifier').click();
    await page.getByPlaceholder('Identifier').fill('Demo_sasd');
    await page.locator('.relative > label').click();
    await page.getByText('Zip From Zip To').click();
    await page.getByPlaceholder('Zip From').click();
    await page.getByPlaceholder('Zip From').fill('234234');
    await page.getByPlaceholder('Zip To').click();
    await page.getByPlaceholder('Zip To').fill('2344234');
    await page.getByRole('button', { name: 'Save Tax Rate' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Tax Rate', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.getByRole('link', { name: 'Tax Rates' }).click();
    await page.locator('div').filter({ hasText: /^1sasdasdweasAI2342344234423432\.0000$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Tax Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.getByRole('button', { name: 'Create Tax Category' }).click();
    await page.getByRole('listbox').selectOption('2');
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('Demo_23w');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_kayuwe');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sdfhg hsgd');
    await page.getByRole('button', { name: 'Save Theme' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Tax Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.locator('div').filter({ hasText: /^1kayuwe23w$/ }).locator('a').first().click();
    await page.getByRole('listbox').selectOption('2');
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('Demo_23w');
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_kayuwe');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sdfhg hsgd');
    await page.getByRole('button', { name: 'Save Theme' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Tax Category', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Taxes' }).click();
    await page.locator('div').filter({ hasText: /^1kayuwe23w$/ }).locator('a').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
