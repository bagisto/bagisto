import { test, expect } from '../../../setup';
import { generateName, generateDescription, generateRandomNumericString } from '../../../utils/faker';

test.describe('Shipping Methods Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/carriers');
    });

    /**
     * Update the Free Shipping Method Configuration.
     */
    test('should configure the free shipping method', async ({ adminPage }) => {
        await adminPage.fill('textarea[name="sales[carriers][free][description]"]', generateDescription(200));

        await adminPage.click('label[for="sales[carriers][free][active]"]');
        // const toggleInput = await adminPage.locator('input[name="sales[carriers][free][active]"]');
        // await expect(toggleInput).toBeChecked();

        // if (await toggleInput.toBeChecked()) {
        //     await adminPage.fill('input[name="sales[carriers][free][title]"]', generateName());
        // }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });


    /**
     * Update the Flat Rate Shipping Configuration.
     */
    test('should configure the flat rate shipping method', async ({ adminPage }) => {
        await adminPage.fill('textarea[name="sales[carriers][free][description]"]', generateDescription(200));

        await adminPage.selectOption('select[name="sales[carriers][flatrate][type]"]', 'per_unit');
        const state = adminPage.locator('select[name="sales[carriers][flatrate][type]"]');
        await expect(state).toHaveValue('per_unit');


        await adminPage.click('label[for="sales[carriers][free][active]"]');
        // const toggleInput = await adminPage.locator('input[name="sales[carriers][free][active]"]');
        // await expect(toggleInput).toBeChecked();

        // if (await toggleInput.toBeChecked()) {
            // await adminPage.fill('input[name="sales[carriers][flatrate][title]"]', generateName());
            // await adminPage.fill('textarea[name="sales[carriers][flatrate][default_rate]"]', generateRandomNumericString(2));
        // }

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
         await expect(adminPage.locator('#app p' , { hasText: 'Configuration saved successfully' })).toBeVisible();
    });
});
