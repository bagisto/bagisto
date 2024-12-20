import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('General of General', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'General Set units options.' }).click();
    await page.getByLabel('Weight Unit Default').selectOption('lbs');
    await page.locator('label > div').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Content of General', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Content Set compare options,' }).click();
    await page.getByLabel('Offer Title').click();
    await page.getByLabel('Offer Title').fill('Demo_Get UPTO 40% OFF on your 1st orderd');
    await page.getByLabel('Redirection Title').click();
    await page.getByLabel('Redirection Title').fill('SHOP NOW');
    await page.getByLabel('Redirection Link').click();
    await page.getByLabel('Redirection Link').click();
    await page.getByLabel('Redirection Link').fill(`${config.baseUrl}/admin/configuration/general/content`);
    await page.getByLabel('Custom CSS Default').click();
    await page.getByLabel('Custom CSS Default').fill('Demo_qqwee');
    await page.getByLabel('Custom Javascript Default').click();
    await page.getByLabel('Custom Javascript Default').fill('Demo_wqqwqw');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Design of General', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Design Set logo and favicon' }).click();
    await page.getByLabel('Logo Image').click();
    await page.getByLabel('Logo Image').setInputFiles('user.png');
    await page.getByLabel('Favicon').click();
    // await page.getByLabel('Favicon').setInputFiles('screenshot_1732536834544.png');
    await page.locator('[id="general\\[design\\]\\[admin_logo\\]\\[favicon\\]\\[delete\\]"]').nth(1).click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Magic AI of General', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Magic AI Set Magic AI options.' }).click();
    await page.locator('label > div').first().click();
    await page.getByLabel('API Key Default').click();
    await page.getByLabel('API Key Default').fill('Demo_asds asdasdsa');
    await page.getByLabel('Organization Default').click();
    await page.getByLabel('Organization Default').fill('Demo_asdqwqw sad');
    await page.getByLabel('LLM API Domain Default').click();
    await page.getByLabel('LLM API Domain Default').fill('Demo_asdqw sdsd');
    await page.locator('div:nth-child(4) > div > .mb-4 > .relative > div').click();
    await page.getByLabel('Product Short Description').click();
    await page.getByLabel('Product Short Description').fill('Demo_asd saadssa');
    await page.getByLabel('Product Description Prompt').click();
    await page.getByLabel('Product Description Prompt').fill('Demo_sadqwdwe sdasdas');
    await page.getByLabel('Category Description Prompt').click();
    await page.getByLabel('Category Description Prompt').fill('Demo_dasdweq');
    await page.getByLabel('CMS Page Content Prompt').click();
    await page.getByLabel('CMS Page Content Prompt').fill('Demo_sadawdqwwwwww');
    await page.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(8) > div > .mb-4 > .relative > div').click();
    await page.locator('[id="general\\[magic_ai\\]\\[review_translation\\]\\[model\\]"]').selectOption('llava');
    await page.locator('div:nth-child(10) > div > .mb-4').first().click();
    await page.locator('div:nth-child(10) > div > .mb-4 > .relative > div').click();
    await page.locator('[id="general\\[magic_ai\\]\\[checkout_message\\]\\[model\\]"]').selectOption('llama2:70b');
    await page.getByLabel('Prompt DefaultEnglish').click();
    await page.getByLabel('Prompt DefaultEnglish').fill('Demo_qwqwasdf');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
