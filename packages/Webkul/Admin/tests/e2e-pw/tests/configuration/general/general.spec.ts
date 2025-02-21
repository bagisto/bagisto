import { test, expect } from '../../../setup';

test.describe('General Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/general');
    });

    /**
     * Update the weight unit.
     */
    test('should update weight unit', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="general[general][locale_options][weight_unit]"]', 'lbs');
        const weightUnitSelect = adminPage.locator('select[name="general[general][locale_options][weight_unit]"]');
        await expect(weightUnitSelect).toHaveValue('lbs');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Enable and Disable Breadcrumbs.
     */
    test('should update breadcrumbs status', async ({ adminPage }) => {
        await adminPage.click('label[for="general[general][breadcrumbs][shop]"]');
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
