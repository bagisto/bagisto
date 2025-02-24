import { test, expect } from '../../../setup';
import { generateName } from '../../../utils/faker';
import { generateRandomUrl } from '../../../utils/form';

test.describe('Content Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/content');
    });

    /**
     * Update Header Offer section.
     */
    test('should update header offer title with redirection title and redirection link', async ({ adminPage }) => {
        await adminPage.locator('input[name="general[content][header_offer][title]"]').fill(generateName());
        await adminPage.locator('input[name="general[content][header_offer][redirection_title]"]').fill(generateName());
        await adminPage.locator('input[name="general[content][header_offer][redirection_link]"]').fill(generateRandomUrl());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update Header Offer section.
     */
    test('should add css and javascript', async ({ adminPage }) => {
        /**
         * Custom CSS and Javascript.
         */
        const cssCode = `.test {\n  display: flex;\n  justify-content: center;\n}`;
        const jsCode = `document.addEventListener('DOMContentLoaded', () => {\n  console.log('JavaScript added successfully');\n});`;
        
        await adminPage.locator('textarea[name="general[content][custom_scripts][custom_css]"]').fill(cssCode);
        await adminPage.locator('textarea[name="general[content][custom_scripts][custom_javascript]"]').fill(jsCode);
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
