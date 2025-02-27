import { test, expect } from '../../setup';
import { generateName, generateSlug } from '../../utils/faker';

test.describe('group management', () => {
        
        /**
         * Test Cases for Creating a New Group.
         */
    test('create group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });
        
        /**
         * Create New Group Modal Form Filling
         */
        await adminPage.click('button.primary-button:visible');
        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug('_'));

        /**
         * Saving the Group Modal Form after filling the details.
         */
        await adminPage.press('input[name="code"]:visible', 'Enter');
        await expect(adminPage.getByText('Group created successfully')).toBeVisible();
    });

        /**
         * Test Cases for Editing a New Group.
         */
    test('edit group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        /**
         * Click on Edit Icon to Edit the Group.
         */
        await adminPage.waitForSelector('span.cursor-pointer.icon-edit');
        const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
        await iconEdit[0].click();

        /**
         * Filling the Edit Group Modal Form with new entries.
         */
        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.fill('input[name="code"]', generateSlug('_'));

        /**
         * Saving the Group Modal Form after filling the details.
         */
        await adminPage.press('input[name="code"]:visible', 'Enter');
        await expect(adminPage.getByText('Group Updated Successfully')).toBeVisible();
    });

        /**
         * Test Cases for Editing a New Group.
         */
    test('delete group', async ({ adminPage }) => {
        await adminPage.goto('admin/customers/groups');
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        /**
         * Click on Edit Icon to Edit the Group.
         */
        await adminPage.waitForSelector('span.cursor-pointer.icon-delete');
        const iconDelete = await adminPage.$$('span.cursor-pointer.icon-delete');
        await iconDelete[0].click();

        /**
         * Select Warning Message box for confirmation to delete selected Search Terms.
         */
        await adminPage.click('button.transparent-button + button.primary-button:visible');
        await expect(adminPage.getByText('Group Deleted Successfully')).toBeVisible();
    });
});