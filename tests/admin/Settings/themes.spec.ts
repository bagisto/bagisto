import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Product Carousel Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('User _test');
    await page.getByPlaceholder('Name').press('CapsLock');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('2');
    await page.locator('select[name="type"]').selectOption('product_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_Product');
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[sort\\]"]').selectOption('name-desc');
    await page.locator('.box-shadow').first().click();
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[limit\\]"]').selectOption('12');
    await page.getByText('Add Filter').click();
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('select[name="key"]').selectOption('color');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Filter').click();
    await page.locator('select[name="key"]').selectOption('featured');
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Category Carousel Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_HJGy');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('32');
    await page.locator('select[name="type"]').selectOption('category_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[sort\\]"]').selectOption('desc');
    await page.getByPlaceholder('Limit').click();
    await page.getByPlaceholder('Limit').fill('32');
    await page.getByText('Add Filter').first().click();
    await page.locator('select[name="key"]').selectOption('name');
    await page.getByPlaceholder('Value').click();
    await page.getByPlaceholder('Value').fill('Demo_32e2eq');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Filter').click();
    await page.locator('select[name="key"]').selectOption('status');
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Static Content Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_asd asd');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('767');
    await page.locator('select[name="type"]').selectOption('static_content');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.locator('.CodeMirror-scroll').click();
    await page.locator('textarea').fill('Demo_sdadwse sdfs');
    await page.getByText('CSS').click();
    await page.locator('.CodeMirror-scroll').click();
    await page.locator('textarea').fill('Demo_sdf sdfdf');
    await page.getByText('Preview').click();
    await page.getByText('Preview').click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Image Slider Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_asdasd');
    await page.locator('.box-shadow > div:nth-child(2) > div:nth-child(2)').click();
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('32');
    await page.locator('select[name="type"]').selectOption('image_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Slider').first().click();
    await page.getByPlaceholder('Image Title').click();
    await page.getByPlaceholder('Image Title').fill('Demo_fdgsdf sfds');
    await page.getByPlaceholder('Link').click();
    await page.getByPlaceholder('Link').click();
    await page.getByPlaceholder('Link').fill(`${config.baseUrl}/admin/settings/themes/edit/16`);
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Image png, jpeg, jpg').click();
    // await page.locator('body').setInputFiles('Screenshot from 2024-12-18 11-00-34.png');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();

  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Footer Link Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_hsdguiwe');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('23');
    await page.locator('select[name="type"]').selectOption('footer_links');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Link').click();
    await page.locator('select[name="column"]').selectOption('column_2');
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_asdw');
    await page.getByPlaceholder('URL').click();
    await page.getByPlaceholder('URL').fill(`${config.baseUrl}/admin/settings/themes/edit/16`);
    await page.getByPlaceholder('Sort Order').first().click();
    await page.getByPlaceholder('Sort Order').first().fill('23');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.px-4 > div:nth-child(6)').click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Services Content Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.getByRole('button', { name: 'Create Theme' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_aSDAS');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('23');
    await page.getByPlaceholder('Sort Order').press('CapsLock');
    await page.locator('select[name="type"]').selectOption('footer_links');
    await page.locator('select[name="type"]').selectOption('services_content');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Services').first().click();
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_asdddddddddas');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sadqweqw');
    await page.getByPlaceholder('Service Icon Class').click();
    await page.getByPlaceholder('Service Icon Class').fill('Demo_dasdqww');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Product Carousel Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^13DefaultDefaultproduct_carouselUser _test2Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('User _test');
    await page.getByPlaceholder('Name').press('CapsLock');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('2');
    await page.locator('select[name="type"]').selectOption('product_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_Product');
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[sort\\]"]').selectOption('name-desc');
    await page.locator('.box-shadow').first().click();
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[limit\\]"]').selectOption('12');
    await page.getByText('Add Filter').click();
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('select[name="key"]').selectOption('color');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Filter').click();
    await page.locator('select[name="key"]').selectOption('featured');
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Category Carousel Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^14DefaultDefaultcategory_carouselHJGy32Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_HJGy');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('32');
    await page.locator('select[name="type"]').selectOption('category_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.locator('select[name="en\\[options\\]\\[filters\\]\\[sort\\]"]').selectOption('desc');
    await page.getByPlaceholder('Limit').click();
    await page.getByPlaceholder('Limit').fill('32');
    await page.getByText('Add Filter').first().click();
    await page.locator('select[name="key"]').selectOption('name');
    await page.getByPlaceholder('Value').click();
    await page.getByPlaceholder('Value').fill('Demo_32e2eq');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Filter').click();
    await page.locator('select[name="key"]').selectOption('status');
    await page.locator('select[name="value"]').selectOption('1');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Static Content Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^15DefaultDefaultstatic_contentasd asd767Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_asd asd');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('767');
    await page.locator('select[name="type"]').selectOption('static_content');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.locator('.CodeMirror-scroll').click();
    await page.locator('textarea').fill('Demo_sdadwse sdfs');
    await page.getByText('CSS').click();
    await page.locator('.CodeMirror-scroll').click();
    await page.locator('textarea').fill('Demo_sdf sdfdf');
    await page.getByText('Preview').click();
    await page.getByText('Preview').click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Image Slider Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^16DefaultDefaultimage_carouselasdasd32Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_asdasd');
    await page.locator('.box-shadow > div:nth-child(2) > div:nth-child(2)').click();
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('32');
    await page.locator('select[name="type"]').selectOption('image_carousel');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Slider').first().click();
    await page.getByPlaceholder('Image Title').click();
    await page.getByPlaceholder('Image Title').fill('Demo_fdgsdf sfds');
    await page.getByPlaceholder('Link').click();
    await page.getByPlaceholder('Link').click();
    await page.getByPlaceholder('Link').fill(`${config.baseUrl}/admin/settings/themes/edit/16`);
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.getByText('Add Image png, jpeg, jpg').click();
    // await page.locator('body').setInputFiles('Screenshot from 2024-12-18 11-00-34.png');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Footer Link Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^17DefaultDefaultfooter_linkshsdguiwe23Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_hsdguiwe');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('23');
    await page.locator('select[name="type"]').selectOption('footer_links');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Link').click();
    await page.locator('select[name="column"]').selectOption('column_2');
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_asdw');
    await page.getByPlaceholder('URL').click();
    await page.getByPlaceholder('URL').fill(`${config.baseUrl}/admin/settings/themes/edit/16`);
    await page.getByPlaceholder('Sort Order').first().click();
    await page.getByPlaceholder('Sort Order').first().fill('23');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.px-4 > div:nth-child(6)').click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Services Content Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^18DefaultDefaultservices_contentaSDASSA23Active$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_aSDAS');
    await page.getByPlaceholder('Sort Order').click();
    await page.getByPlaceholder('Sort Order').fill('23');
    await page.getByPlaceholder('Sort Order').press('CapsLock');
    await page.locator('select[name="type"]').selectOption('footer_links');
    await page.locator('select[name="type"]').selectOption('services_content');
    await page.getByRole('button', { name: 'Save Theme' }).click();
    await page.getByText('Add Services').first().click();
    await page.getByPlaceholder('Title').click();
    await page.getByPlaceholder('Title').fill('Demo_asdddddddddas');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sadqweqw');
    await page.getByPlaceholder('Service Icon Class').click();
    await page.getByPlaceholder('Service Icon Class').fill('Demo_dasdqww');
    await page.locator('button').filter({ hasText: /^Save$/ }).click();
    await page.locator('.relative > label').click();
    await page.getByRole('button', { name: 'Save' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Theme', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Settings' }).click();
    await page.getByRole('link', { name: 'Themes' }).click();
    await page.locator('div').filter({ hasText: /^18DefaultDefaultservices_contentaSDASSA23Active$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
