import { test, expect } from '@playwright/test';
import config from '../../../Config/config';

test('Create Cart Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Cart Rules' }).click();
    await page.getByRole('link', { name: 'Create Cart Rule' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_jksdhwsd');
    await page.getByPlaceholder('Name').press('Tab');
    await page.getByPlaceholder('Description').fill('Demo_usdwoe');
    await page.getByPlaceholder('Description').press('Tab');
    await page.locator('#coupon_type').selectOption('1');
    await page.getByPlaceholder('Coupon Code').click();
    await page.getByPlaceholder('Coupon Code').fill('ewiyduew');
    await page.getByText('General Name Description').click();
    await page.getByPlaceholder('Uses Per Coupon').click();
    await page.getByPlaceholder('Uses Per Coupon').fill('23');
    await page.getByPlaceholder('Uses Per Customer').click();
    await page.getByPlaceholder('Uses Per Customer').fill('32');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[0\\]\\[attribute\\]"]').selectOption('cart_item|base_total');
    await page.locator('select[name="conditions\\[0\\]\\[operator\\]"]').selectOption('<=');
    await page.locator('.mt-4 > div').click();
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').fill('32232');
    await page.getByText('Add Condition').click();
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[2\\]\\[attribute\\]"]').selectOption('product|attribute_family_id');
    await page.locator('select[name="conditions\\[2\\]\\[operator\\]"]').selectOption('!=');
    await page.locator('select[name="conditions\\[2\\]\\[value\\]"]').selectOption('1');
    await page.locator('#action_type').selectOption('by_fixed');
    await page.getByPlaceholder('Discount Amount').click();
    await page.getByPlaceholder('Discount Amount').fill('03232');
    await page.getByPlaceholder('Maximum Quantity allowed to').click();
    await page.getByPlaceholder('Maximum Quantity allowed to').fill('32');
    await page.locator('#apply_to_shipping').selectOption('1');
    await page.locator('#free_shipping').selectOption('1');
    await page.getByPlaceholder('Buy X Quantity').click();
    await page.getByPlaceholder('Buy X Quantity').fill('023');
    await page.getByText('End Of Other Rules').click();
    await page.getByPlaceholder('To', { exact: true }).click();
    await page.getByLabel('December 5,').nth(1).click();
    await page.getByPlaceholder('From').click();
    await page.getByLabel('December 1,').first().click();
    await page.locator('.px-4 > div:nth-child(4)').click();
    await page.locator('.relative > label').click();
    await page.locator('#customer_group__3').nth(1).click();
    await page.getByLabel('Wholesale').click();
    await page.locator('.mb-2\\.5 > .mb-4').first().click();
    await page.locator('#channel__1').nth(1).click();
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('21');
    await page.getByRole('button', { name: 'Save Cart Rule' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Cart Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Cart Rules' }).click();
    await page.locator('div').filter({ hasText: /^1jksdhwsdewiyduew2024-12-01 12:00:002024-12-05 12:00:00Active21$/ }).locator('span').first().click();
    await page.getByRole('link', { name: 'Create Cart Rule' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_jksdhwsd');
    await page.getByPlaceholder('Name').press('Tab');
    await page.getByPlaceholder('Description').fill('Demo_usdwoe');
    await page.getByPlaceholder('Description').press('Tab');
    await page.locator('#coupon_type').selectOption('1');
    await page.getByPlaceholder('Coupon Code').click();
    await page.getByPlaceholder('Coupon Code').fill('ewiyduew');
    await page.getByText('General Name Description').click();
    await page.getByPlaceholder('Uses Per Coupon').click();
    await page.getByPlaceholder('Uses Per Coupon').fill('23');
    await page.getByPlaceholder('Uses Per Customer').click();
    await page.getByPlaceholder('Uses Per Customer').fill('32');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[0\\]\\[attribute\\]"]').selectOption('cart_item|base_total');
    await page.locator('select[name="conditions\\[0\\]\\[operator\\]"]').selectOption('<=');
    await page.locator('.mt-4 > div').click();
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').fill('32232');
    await page.getByText('Add Condition').click();
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[2\\]\\[attribute\\]"]').selectOption('product|attribute_family_id');
    await page.locator('select[name="conditions\\[2\\]\\[operator\\]"]').selectOption('!=');
    await page.locator('select[name="conditions\\[2\\]\\[value\\]"]').selectOption('1');
    await page.locator('#action_type').selectOption('by_fixed');
    await page.getByPlaceholder('Discount Amount').click();
    await page.getByPlaceholder('Discount Amount').fill('03232');
    await page.getByPlaceholder('Maximum Quantity allowed to').click();
    await page.getByPlaceholder('Maximum Quantity allowed to').fill('32');
    await page.locator('#apply_to_shipping').selectOption('1');
    await page.locator('#free_shipping').selectOption('1');
    await page.getByPlaceholder('Buy X Quantity').click();
    await page.getByPlaceholder('Buy X Quantity').fill('023');
    await page.getByText('End Of Other Rules').click();
    await page.getByPlaceholder('To', { exact: true }).click();
    await page.getByLabel('December 5,').nth(1).click();
    await page.getByPlaceholder('From').click();
    await page.getByLabel('December 1,').first().click();
    await page.locator('.px-4 > div:nth-child(4)').click();
    await page.locator('.relative > label').click();
    await page.locator('#customer_group__3').nth(1).click();
    await page.getByLabel('Wholesale').click();
    await page.locator('.mb-2\\.5 > .mb-4').first().click();
    await page.locator('#channel__1').nth(1).click();
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('21');
    await page.getByRole('button', { name: 'Save Cart Rule' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Cart Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Cart Rules' }).click();
    await page.locator('div').filter({ hasText: /^1jksdhwsdewiyduew2024-12-01 12:00:002024-12-05 12:00:00Active21$/ }).locator('span').nth(2).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Create Catalog Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Create Catalog Rule' }).click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('test');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sduy hsdgyu');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[0\\]\\[attribute\\]"]').selectOption('product|price');
    await page.locator('select[name="conditions\\[0\\]\\[operator\\]"]').selectOption('>=');
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').click();
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').fill('7625342673');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[1\\]\\[attribute\\]"]').selectOption('product|special_price_to');
    await page.locator('select[name="conditions\\[1\\]\\[operator\\]"]').selectOption('>=');
    await page.locator('input[name="conditions\\[1\\]\\[value\\]"]').click();
    await page.getByLabel('December 17,').nth(2).click();
    await page.locator('input[name="conditions\\[1\\]\\[value\\]"]').fill('2024-12-17');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[2\\]\\[attribute\\]"]').selectOption('product|category_ids');
    await page.locator('select[name="conditions\\[2\\]\\[operator\\]"]').selectOption('!{}');
    await page.locator('label').filter({ hasText: 'Men' }).locator('span').click();
    await page.locator('label').filter({ hasText: 'Men' }).locator('span').click();
    await page.locator('label').filter({ hasText: 'Root' }).locator('span').click();await page.locator('#discount_amount').click();
    await page.locator('#discount_amount').fill('64');
    await page.locator('#end_other_rules').selectOption('1');
    await page.getByPlaceholder('From').click();
    await page.getByLabel('December 9,').first().click();
    await page.getByPlaceholder('From').fill('2024-12-09');
    await page.getByPlaceholder('To').click();
    await page.getByPlaceholder('To').fill('2024-12-25');
    await page.locator('.relative > label').click();
    await page.locator('#customer_group__3').nth(1).click();
    await page.locator('#customer_group__2').nth(1).click();
    await page.locator('#customer_group__1').nth(1).click();
    await page.locator('#channel__1').nth(1).click();
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('Demo_wqdqedqw');
    await page.getByRole('button', { name: 'Save Catalog Rule' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Edit Catalog Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.locator('div').filter({ hasText: /^1User2024-12-092024-12-25Active0$/ }).locator('span').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('test');
    await page.getByPlaceholder('Description').click();
    await page.getByPlaceholder('Description').fill('Demo_sduy hsdgyu');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[0\\]\\[attribute\\]"]').selectOption('product|price');
    await page.locator('select[name="conditions\\[0\\]\\[operator\\]"]').selectOption('>=');
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').click();
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').fill('7625342673');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[1\\]\\[attribute\\]"]').selectOption('product|special_price_to');
    await page.locator('select[name="conditions\\[1\\]\\[operator\\]"]').selectOption('>=');
    await page.locator('input[name="conditions\\[1\\]\\[value\\]"]').click();
    await page.getByLabel('December 17,').nth(2).click();
    await page.locator('input[name="conditions\\[1\\]\\[value\\]"]').fill('2024-12-17');
    await page.getByText('Add Condition').click();
    await page.locator('[id="conditions\\[2\\]\\[attribute\\]"]').selectOption('product|category_ids');
    await page.locator('select[name="conditions\\[2\\]\\[operator\\]"]').selectOption('!{}');
    await page.locator('label').filter({ hasText: 'Men' }).locator('span').click();
    await page.locator('label').filter({ hasText: 'Men' }).locator('span').click();
    await page.locator('label').filter({ hasText: 'Root' }).locator('span').click();await page.locator('#discount_amount').click();
    await page.locator('#discount_amount').fill('64');
    await page.locator('#end_other_rules').selectOption('1');
    await page.getByPlaceholder('From').click();
    await page.getByLabel('December 9,').first().click();
    await page.getByPlaceholder('From').fill('2024-12-09');
    await page.getByPlaceholder('To').click();
    await page.getByPlaceholder('To').fill('2024-12-25');
    await page.locator('.relative > label').click();
    await page.locator('#customer_group__3').nth(1).click();
    await page.locator('#customer_group__2').nth(1).click();
    await page.locator('#customer_group__1').nth(1).click();
    await page.locator('#channel__1').nth(1).click();
    await page.getByPlaceholder('Priority').click();
    await page.getByPlaceholder('Priority').fill('Demo_wqdqedqw');
    await page.getByRole('button', { name: 'Save Catalog Rule' }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});

test('Delete Catalog Rule', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.locator('div').filter({ hasText: /^1User2024-12-092024-12-25Active0$/ }).locator('span').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
  try {
    await page.waitForNavigation({ timeout: 5000 });
    console.log(page.url());
  } catch(e) {
    console.log(page.url());
  }
});
