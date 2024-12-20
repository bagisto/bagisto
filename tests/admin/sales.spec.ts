import { test, expect } from '@playwright/test';
import config from '../../Config/config';

test('Create Orders', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.getByRole('button', { name: 'Create Order' }).click();
    await page.getByPlaceholder('Search by email or name').click();
    await page.getByPlaceholder('Search by email or name').fill('Demo_Webkul');
    await page.getByPlaceholder('Search by email or name').press('Enter');
    await page.getByPlaceholder('Search by email or name').press('Enter');
    await page.getByRole('button', { name: 'Create Customer' }).click();
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('Demo_User');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').fill('Demo_test');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('Demo_rte21@gmail.com');
    await page.getByPlaceholder('Contact Number').click();
    await page.getByPlaceholder('Contact Number').fill('09812878623');
    await page.getByPlaceholder('Date of Birth').click();
    await page.getByLabel('December 6,').click();
    await page.getByPlaceholder('Date of Birth').fill('2024-12-06');
    await page.locator('#gender').selectOption('Female');
    await page.locator('#customerGroup').selectOption('3');
    await page.getByRole('button', { name: 'Save customer' }).click();
    await page.getByRole('button', { name: 'Add Product' }).click();
    await page.getByPlaceholder('Search by name').click();
    await page.getByPlaceholder('Search by name').fill('arct');
    await page.locator('input:nth-child(3)').first().click();
    await page.locator('input:nth-child(3)').first().fill('13');
    await page.locator('form > .grid > .cursor-pointer').first().click();
    await page.getByText('+').click();
    await page.getByText('-', { exact: true }).click();
    await page.getByText('Add Address').click();
    await page.getByPlaceholder('Company Name').click();
    await page.getByPlaceholder('Company Name').fill('Demo_webkul');
    await page.getByPlaceholder('Vat ID').click();
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('Demo_User_test');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').fill('Demo_test');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('Demo_wey@dg.sdd');
    await page.getByPlaceholder('Street Address').click();
    await page.getByPlaceholder('Street Address').fill('Demo_hsdguywe');
    await page.locator('select[name="billing\\.country"]').selectOption('BS');
    await page.locator('select[name="billing\\.country"]').selectOption('WF');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_dwedwd');
    await page.getByPlaceholder('City').click();
    await page.getByPlaceholder('City').fill('Demo_dewdw');
    await page.getByPlaceholder('Zip/Postcode').click();
    await page.getByPlaceholder('Zip/Postcode').fill('2312');
    await page.getByPlaceholder('Telephone').click();
    await page.getByPlaceholder('Telephone').fill('98765432');
    await page.getByPlaceholder('Telephone').press('Enter');
    await page.getByRole('button', { name: 'Save' }).click();
    await page.getByText('Use same address for shipping?').click();
    await page.locator('#address-step-container div').filter({ hasText: 'Shipping Address Add Address' }).nth(2).click();
    await page.getByText('Add Address').click();
    await page.getByPlaceholder('Company Name').click();
    await page.getByPlaceholder('Company Name').fill('Demo_sdsad');
    await page.getByPlaceholder('First Name').click();
    await page.getByPlaceholder('First Name').fill('Demo_adasdasa');
    await page.getByPlaceholder('Last Name').click();
    await page.getByPlaceholder('Last Name').dblclick();
    await page.getByPlaceholder('Last Name').fill('Demo_adsdasda');
    await page.getByPlaceholder('email@example.com').click();
    await page.getByPlaceholder('email@example.com').fill('Demo_dasd@dwew.fgh');
    await page.getByPlaceholder('Street Address').click();
    await page.getByPlaceholder('Street Address').fill('Demo_qwerwe');
    await page.locator('select[name="shipping\\.country"]').selectOption('AC');
    await page.getByPlaceholder('State').click();
    await page.getByPlaceholder('State').fill('Demo_wewqe');
    await page.getByPlaceholder('City').click();
    await page.getByPlaceholder('City').fill('Demo_qweqw3e1');
    await page.getByPlaceholder('Zip/Postcode').click();
    await page.getByPlaceholder('Zip/Postcode').fill('23123');
    await page.getByPlaceholder('Telephone').click();
    await page.getByPlaceholder('Telephone').fill('21111111143');
    await page.getByRole('button', { name: 'Save' }).click();
    await page.getByRole('button', { name: 'Proceed' }).click();
    await page.locator('div').filter({ hasText: /^Free Shipping$/ }).getByRole('paragraph').click();
    await page.getByRole('button', { name: 'Place Order' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Comment on Order', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('.row > div:nth-child(4) > a').first().click();
    await page.getByPlaceholder('Write your comment').click();
    await page.getByPlaceholder('Write your comment').fill('Demo_asdasd sdcsda');
    await page.getByRole('button', { name: '' }).click();
    await page.getByLabel('Submit Comment').click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Cancel Order', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('.row > div:nth-child(4) > a').first().click();
    await page.locator('.icon-cancel').click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Reorder', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('.row > div:nth-child(4) > a').first().click();
    await page.getByRole('link', { name: ' Reorder' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Invoice', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('div:nth-child(7) > div:nth-child(4) > a').click();
    await page.getByText('Invoice', { exact: true }).click();
    await page.locator('#can_create_transaction').nth(1).click();
    await page.getByRole('button', { name: 'Create Invoice' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Shipment', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('div:nth-child(7) > div:nth-child(4) > a').click();
    await page.getByText('Ship', { exact: true }).click();
    await page.getByPlaceholder('Tracking Number').click();
    await page.getByPlaceholder('Tracking Number').fill('23');
    await page.getByPlaceholder('Carrier Name').click();
    await page.getByPlaceholder('Carrier Name').fill('Demo_dsfsdf');
    await page.locator('[id="shipment\\[source\\]"]').selectOption('1');
    await page.getByRole('button', { name: 'Create Shipment' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Refund', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.locator('div:nth-child(7) > div:nth-child(4) > a').click();
    await page.locator('.icon-cancel').click();
    await page.getByRole('button', { name: 'Refund' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Mail Invoice', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.getByRole('link', { name: 'Invoices' }).click();
    await page.locator('div').filter({ hasText: /^42\$55\.00Paid2024-12-18 15:22:57$/ }).locator('span').click();
    await page.getByRole('button', { name: ' Send Duplicate Invoice' }).click();
    await page.locator('#email').click();
    await page.locator('#email').fill('testUser@gmail.com');
    await page.getByRole('button', { name: 'Send', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Print Invoice', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Sales' }).click();
    await page.getByRole('link', { name: 'Invoices' }).click();
    await page.locator('div').filter({ hasText: /^42\$55\.00Paid2024-12-18 15:22:57$/ }).locator('span').click();
    const downloadPromise = page.waitForEvent('download');
    await page.getByRole('link', { name: ' Print' }).click();
    const download = await downloadPromise;
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
