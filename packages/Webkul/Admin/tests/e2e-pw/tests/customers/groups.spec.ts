import { test, expect, config } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('group management', () => {
    test('create group', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/groups`);
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.click('button.primary-button:visible');

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
        await adminPage.fill('input[name="code"]', concatenatedNames);

        await adminPage.press('input[name="code"]:visible', 'Enter');

        await expect(adminPage.getByText('Group created successfully')).toBeVisible();
    });

    test('edit group', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/groups`);
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-edit');
        const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
        await iconEdit[0].click();

        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => forms.generateRandomProductName())
            .join(' ')
            .replaceAll(' ', '');

        await adminPage.fill('input[name="name"]', forms.generateRandomStringWithSpaces(Math.floor(Math.random() * 200)));
        await adminPage.fill('input[name="code"]', concatenatedNames);

        await adminPage.press('input[name="code"]:visible', 'Enter');

        await expect(adminPage.getByText('Group Updated Successfully')).toBeVisible();
    });

    test('delete group', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/customers/groups`);
        await adminPage.waitForSelector('button.primary-button:visible', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-delete');
        const iconDelete = await adminPage.$$('span.cursor-pointer.icon-delete');
        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Group Deleted Successfully')).toBeVisible();
    });
});
