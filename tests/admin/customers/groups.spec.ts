import { test, expect, config } from '../../utils/setup';

test('Create Group', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Groups' }).click();
    await page.getByRole('button', { name: 'Create Group' }).click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('Demo_fsfwew');
    await page.locator('.box-shadow > div:nth-child(2) > div:nth-child(2)').click();
    await page.getByPlaceholder('Name').fill('Demo_wewerw');
    await page.getByRole('button', { name: 'Save Group' }).click();

    await expect(page.getByText('Group created successfully')).toBeVisible();
});

test('Edit Group', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Groups' }).click();
    await page.locator('div').filter({ hasText: /^4fsfwewwewerw$/ }).locator('a').first().click();
    await page.getByPlaceholder('Code').click();
    await page.getByPlaceholder('Code').fill('Demo_fsfwew');
    await page.locator('.box-shadow > div:nth-child(2) > div:nth-child(2)').click();
    await page.getByPlaceholder('Name').fill('Demo_wewerw');
    await page.getByRole('button', { name: 'Save Group' }).click();

    await expect(page.getByText('Group Updated Successfully')).toBeVisible();
});

test('Delete Group', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Customers' }).click();
    await page.getByRole('link', { name: 'Groups' }).click();
    await page.locator('div').filter({ hasText: /^4fsfwewwewerw$/ }).locator('a').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Group Deleted Successfully')).toBeVisible();
});
