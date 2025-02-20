import { test, expect } from '../../setup';
import { generateName, generateSlug } from '../../utils/faker';

test.describe('group management', () => {
    test('create group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.click('button.primary-button:visible');

        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug('_'));

        await adminPage.press('input[name="code"]:visible', 'Enter');

        await expect(adminPage.getByText('Group created successfully')).toBeVisible();
    });

    test('edit group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-edit');
        const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
        await iconEdit[0].click();

        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug('_'));

        await adminPage.press('input[name="code"]:visible', 'Enter');

        await expect(adminPage.getByText('Group Updated Successfully')).toBeVisible();
    });

    test('delete group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-delete');
        const iconDelete = await adminPage.$$('span.cursor-pointer.icon-delete');
        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Group Deleted Successfully')).toBeVisible();
    });
});
