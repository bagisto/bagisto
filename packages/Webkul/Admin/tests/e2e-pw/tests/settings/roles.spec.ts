import { test, expect, config } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('role management', () => {
    test('create role', async ({ adminPage }) => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/roles`);

        await adminPage.click('a.primary-button:visible');

        await adminPage.click('select[name="permission_type"]');

        const select = await adminPage.$('select[name="permission_type"]');

        const option = Math.random() > 0.5 ? 'custom' : 'all';

        await select.selectOption({ value: option });

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('Roles Created Successfully')).toBeVisible();
    });

    test('edit role', async () => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/roles`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconEdit = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-edit"]');

        await iconEdit[0].click();

        await adminPage.click('select[name="permission_type"]');

        const select = await adminPage.$('select[name="permission_type"]');

        const option = Math.random() > 0.5 ? 'custom' : 'all';

        await select.selectOption({ value: option });

        const inputs = await adminPage.$$('textarea.rounded-md:visible, input[type="text"].rounded-md:visible');

        for (let input of inputs) {
            await input.fill(forms.generateRandomStringWithSpaces(200));
        }

        const checkboxs = await adminPage.$$('input[type="checkbox"] + span');

        for (let checkbox of checkboxs) {
            let i = Math.floor(Math.random() * 10) + 1;

            if (i % 3 == 1) {
                await checkbox.click();
            }
        }

        await inputs[0].press('Enter');

        await expect(adminPage.getByText('Roles is updated successfully')).toBeVisible();
    });

    test('delete role', async () => {
        await adminPage.goto(`${config.baseUrl}/admin/settings/roles`);

        await adminPage.waitForSelector('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        const iconDelete = await adminPage.$$('span[class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center icon-delete"]');

        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('Roles is deleted successfully')).toBeVisible();
    });
});
