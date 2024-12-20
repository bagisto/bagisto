import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Shipping Settings of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Shipping Settings Configure' }).click();
    await page.getByLabel('Country DefaultEnglish').selectOption('IN');
    await page.getByLabel('State DefaultEnglish').selectOption('UP');
    await page.getByLabel('City DefaultEnglish').click();
    await page.getByLabel('City DefaultEnglish').fill('Noida');
    await page.getByLabel('Street Address DefaultEnglish').click();
    await page.getByLabel('Street Address DefaultEnglish').fill('Sector 22');
    await page.getByLabel('Zip DefaultEnglish').click();
    await page.getByLabel('Zip DefaultEnglish').fill('201302');
    await page.getByLabel('Store Name DefaultEnglish').click();
    await page.getByLabel('Store Name DefaultEnglish').fill('Demo_qwertyuiop');
    await page.getByLabel('Vat Number Default').click();
    await page.getByLabel('Contact Number Default').click();
    await page.getByLabel('Contact Number Default').fill('9876543210');
    await page.getByLabel('Bank Details DefaultEnglish').click();
    await page.getByLabel('Bank Details DefaultEnglish').fill('Demo_sjdhjd shdgyuw');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Shipping Methods of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Shipping Methods Configure' }).click();
    await page.locator('input[name="sales\\[carriers\\]\\[free\\]\\[title\\]"]').click();
    await page.locator('input[name="sales\\[carriers\\]\\[free\\]\\[title\\]"]').fill('Demo_Free Shipping');
    await page.locator('[id="sales\\[carriers\\]\\[free\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[carriers\\]\\[free\\]\\[description\\]"]').fill('Demo_Free Shipping');
    await page.locator('label > div').first().click();
    await page.locator('label > div').first().click();
    await page.locator('[id="sales\\[carriers\\]\\[flatrate\\]\\[title\\]"]').click();
    await page.locator('[id="sales\\[carriers\\]\\[flatrate\\]\\[title\\]"]').fill('Demo_Flat Rate');
    await page.locator('[id="sales\\[carriers\\]\\[flatrate\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[carriers\\]\\[flatrate\\]\\[description\\]"]').fill('Demo_Flat Rate Shipping');
    await page.getByLabel('Rate Default').click();
    await page.getByLabel('Rate Default').fill('104');
    await page.getByLabel('Type Default').selectOption('per_order');
    await page.locator('div:nth-child(10) > .mb-4 > .relative > div').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Payment Methods of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Payment Methods Set payment' }).click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[title\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[title\\]"]').fill('Demo_PayPal Smart Buttonfcg');
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[description\\]"]').fill('Demo_PayPaldfgdg');
    await page.getByLabel('Client ID Default').click();
    await page.getByLabel('Client ID Default').fill('Demo_sbdfgdf');
    await page.getByLabel('Client Secret Default').click();
    await page.getByLabel('Client Secret Default').fill('Demo_dfgdrtger');
    await page.getByLabel('Accepted currencies Default').click();
    await page.getByLabel('Accepted currencies Default').fill('Demo_fdgrter');
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_smart_button\\]\\[sort\\]"]').selectOption('2');
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[title\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[title\\]"]').fill('Demo_Cash On Deliverygdgdf');
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[description\\]"]').fill('Demo_Cash On Deliverydfgdfger');
    await page.getByLabel('Instructions DefaultEnglish').click();
    await page.getByLabel('Instructions DefaultEnglish').fill('Demo_dfgdrt4t');
    await page.locator('div:nth-child(10) > .mb-4 > .relative > div').first().click();
    await page.getByLabel('Set the invoice status after').selectOption('paid');
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[order_status\\]"]').selectOption('pending_payment');
    await page.locator('div:nth-child(4) > div:nth-child(16) > .mb-4 > .relative > div').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[sort\\]"]').selectOption('3');
    await page.locator('div:nth-child(10) > .mb-4 > .relative > div').first().click();
    await page.locator('[id="sales\\[payment_methods\\]\\[cashondelivery\\]\\[generate_invoice\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_standard\\]\\[sort\\]"]').selectOption('2');
    await page.locator('div:nth-child(8) > div:nth-child(10) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(12) > .mb-4 > .relative > div').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_standard\\]\\[description\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_standard\\]\\[description\\]"]').fill('Demo_PayPal Standardert');
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_standard\\]\\[title\\]"]').click();
    await page.locator('[id="sales\\[payment_methods\\]\\[paypal_standard\\]\\[title\\]"]').fill('Demo_PayPal Standarderter');
    await page.getByLabel('Business Account Default').click();
    await page.getByLabel('Business Account Default').fill('test@webkul.comre');
    await page.getByLabel('Send Check to DefaultEnglish').click();
    await page.getByLabel('Send Check to DefaultEnglish').fill('Demo_reter');
    await page.locator('[id="sales\\[payment_methods\\]\\[moneytransfer\\]\\[order_status\\]"]').selectOption('pending_payment');
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.getByLabel('Set the invoice status after').selectOption('pending');
    await page.locator('div:nth-child(4) > div:nth-child(16) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(10) > .mb-4 > .relative > div').first().click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Order Settings of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Order Settings Set order' }).click();
    await page.getByLabel('Order Number Prefix Default').click();
    await page.getByLabel('Order Number Prefix Default').fill('Demo_sdfwee');
    await page.getByLabel('Order Number Length Default').click();
    await page.getByLabel('Order Number Length Default').fill('32');
    await page.getByLabel('Order Number Suffix Default').click();
    await page.getByLabel('Order Number Suffix Default').fill('Demo_frwerf');
    await page.getByLabel('Order Number Generator Default').click();
    await page.getByLabel('Order Number Generator Default').fill('Demo_erwr34');
    await page.locator('.mt-6 > div:nth-child(4) > div > .mb-4').first().click();
    await page.locator('label > div').first().click();
    await page.getByLabel('Minimum Order Amount Default').click();
    await page.getByLabel('Minimum Order Amount Default').fill('324');
    await page.locator('div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.getByLabel('Description Default').click();
    await page.getByLabel('Description Default').fill('Demo_dfgter df');
    await page.locator('div:nth-child(6) > div > .mb-4 > .relative > div').first().click();
    await page.locator('div:nth-child(6) > div > .mb-4 > .relative > div').first().click();
    await page.getByText('Shop Reorder Enable or').click();
    await page.getByText('Shop Reorder Enable or').click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.getByLabel('Shop Reorder').click();
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Invoice Settings of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Invoice Settings Set invoice' }).click();
    await page.getByLabel('Invoice Number Prefix').click();
    await page.getByLabel('Invoice Number Prefix').fill('Demo_asdadas');
    await page.getByLabel('Invoice Number Length').click();
    await page.getByLabel('Invoice Number Length').fill('3');
    await page.getByLabel('Invoice Number Suffix').click();
    await page.getByLabel('Invoice Number Suffix').fill('Demo_we23');
    await page.getByLabel('Invoice Number Generator').click();
    await page.getByLabel('Invoice Number Generator').fill('Demo_we23edw');
    await page.getByLabel('Due Duration Default').click();
    await page.getByLabel('Due Duration Default').fill('23');
    await page.locator('label > div').first().click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.getByLabel('Footer text DefaultEnglish').click();
    await page.getByLabel('Footer text DefaultEnglish').fill('Demo_fdwsewr');
    await page.getByLabel('Maximum limit of reminders').click();
    await page.getByLabel('Maximum limit of reminders').fill('23');
    await page.getByLabel('Interval between reminders').selectOption('P2W');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Taxes of Sales', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Taxes Taxes are mandatory' }).click();
    await page.getByLabel('Product Default Tax Category').click();
    await page.getByLabel('Calculation Based On').selectOption('billing_address');
    await page.getByText('Calculation Based On Shipping AddressBilling AddressShipping OriginProduct').click();
    await page.getByLabel('Product Prices').selectOption('including_tax');
    await page.getByLabel('Shipping Prices').selectOption('including_tax');
    await page.getByLabel('Default Country').selectOption('AU');
    await page.getByLabel('Default State').click();
    await page.getByLabel('Default State').fill('Demo_asdwe');
    await page.getByLabel('Default Post Code').click();
    await page.getByLabel('Default Post Code').fill('23424');
    await page.locator('[id="sales\\[taxes\\]\\[shopping_cart\\]\\[display_prices\\]"]').selectOption('including_tax');
    await page.locator('[id="sales\\[taxes\\]\\[shopping_cart\\]\\[display_subtotal\\]"]').selectOption('both');
    await page.locator('[id="sales\\[taxes\\]\\[shopping_cart\\]\\[display_shipping_amount\\]"]').selectOption('both');
    await page.locator('[id="sales\\[taxes\\]\\[sales\\]\\[display_prices\\]"]').selectOption('including_tax');
    await page.locator('[id="sales\\[taxes\\]\\[sales\\]\\[display_subtotal\\]"]').selectOption('both');
    await page.locator('[id="sales\\[taxes\\]\\[sales\\]\\[display_shipping_amount\\]"]').selectOption('both');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Checkout of Customer', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Configure' }).click();
    await page.getByRole('link', { name: 'Checkout Set guest checkout,' }).click();
    await page.locator('label > div').first().click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(6) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(8) > .mb-4 > .relative > div').click();
    await page.locator('div:nth-child(4) > .mb-4 > .relative > div').click();
    await page.getByLabel('Summary').selectOption('display_item_quantity');
    await page.getByLabel('Mini Cart Offer Information').click();
    await page.getByLabel('Mini Cart Offer Information').fill('Get Up To 30% OFF on your 1st orderawsqw');
    await page.getByRole('button', { name: 'Save Configuration' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
