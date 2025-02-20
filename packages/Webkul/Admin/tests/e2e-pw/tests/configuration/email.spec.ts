import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

test.describe('email configuration', () => {
    test('Settings of Email', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/emails/configure');

        await adminPage.click('input[type="text"].rounded-md:visible');

        const inputs = await adminPage.$$('input[type="text"].rounded-md:visible');

        let i = 0;

        for (let input of inputs) {
            if (i % 2 == 0) {
                await input.fill(forms.generateRandomStringWithSpaces(50));
            } else {
                await input.fill(forms.form.email);
            }

            i++;
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    test('Notifications of Email', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/emails/general');

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
