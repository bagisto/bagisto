import { test, expect, config } from '../../utils/setup';

test('Update Status of Review', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Reviews' }).click();
    await page.locator('div:nth-child(4) > a:nth-child(2)').click();
    await page.locator('select[name="status"]').selectOption('approved');
    await page.getByRole('button', { name: 'Save' }).click();

    await expect(page.getByText('Review Update Successfully')).toBeVisible();
});

test('Delete Review', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Reviews' }).click();
    await page.locator('.row > div:nth-child(4) > a').first().click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Review Deleted Successfully')).toBeVisible();
});

test('Mass Delete Reviews', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Reviews' }).click();
    await page.locator('div').filter({ hasText: /^NameProductStatus$/ }).locator('label span').click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Selected Review Deleted Successfully')).toBeVisible();
});

test('Mass Update Reviews', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Reviews' }).click();
    await page.locator('div').filter({ hasText: /^NameProductStatus$/ }).locator('label span').click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Disapproved' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Selected Review Updated Successfully')).toBeVisible();
});
