import { test, expect, config } from '../utils/setup';

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

    await page.waitForSelector('text=CMS created successfully.', { timeout: 5000 });
});

test('Edit Page', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' CMS' }).click();
    await page.locator('div').locator('span.cursor-pointer.rounded-md.icon-edit').nth(2).click();
    await page.getByPlaceholder('Page Title').fill('Demo_okhjasd');
    await page.locator('label:text("Default")').click();
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

    await page.waitForSelector('text=CMS updated successfully.', { timeout: 5000 });
});

test('Delete Page', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' CMS' }).click();
    await page.locator('div').locator('span.cursor-pointer.rounded-md.icon-delete').nth(2).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await page.waitForSelector('text=CMS deleted successfully.', { timeout: 5000 });
});

test('Mass Delete Pages', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' CMS' }).click();
    await page.locator('span.icon-uncheckbox').nth(2).click();
    await page.locator('span.icon-uncheckbox').nth(3).click();
    await page.locator('span.icon-uncheckbox').nth(4).click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await page.waitForSelector('text=Selected Data Deleted Successfully', { timeout: 5000 });
});
