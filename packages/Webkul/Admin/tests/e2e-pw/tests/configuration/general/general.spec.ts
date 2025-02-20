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
        const weightUnitSelect = adminPage.getByLabel('Weight Unit Default');
        await weightUnitSelect.selectOption('kgs');
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(weightUnitSelect).toHaveValue('kgs');

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Enable and Disable Breadcrumbs.
     */
    test('should update breadcrumbs status', async ({ adminPage }) => {
        const breadcrumbToggle = adminPage.locator('label > div');
        await breadcrumbToggle.click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(breadcrumbToggle).toBeVisible(); 

        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
