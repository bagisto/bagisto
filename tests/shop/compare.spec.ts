import { test, expect, config } from '../utils/setup';

test('Add', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('div:nth-child(2) > .-mt-9 > .action-items > .icon-compare').first().click();
    await page.locator('.action-items > .icon-compare').first().click();
    await page.locator('div:nth-child(3) > .-mt-9 > .action-items > .icon-compare').first().click();

    await page.waitForSelector('text=Item added successfully to compare list', { timeout: 5000 });
});

test('Remove', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('div:nth-child(2) > .-mt-9 > .action-items > .icon-compare').first().click();
    await page.locator('.action-items > .icon-compare').first().click();
    await page.locator('div:nth-child(3) > .-mt-9 > div').first().click();
    await page.getByRole('link', { name: 'Compare' }).click();
    await page.locator('.relative > .icon-cancel').first().click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();
});

test('Remove all', async ({page}) => {
    await page.goto(`${config.baseUrl}`);
    await page.locator('div:nth-child(2) > .-mt-9 > .action-items > .icon-compare').first().click();
    await page.locator('.action-items > .icon-compare').first().click();
    await page.locator('div:nth-child(3) > .-mt-9 > div').first().click();
    await page.getByRole('link', { name: 'Compare' }).click();
    await page.getByText('Delete All', { exact: true }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await page.waitForSelector('text=All items removed successfully.', { timeout: 5000 });
});
