import { test, expect } from '../../../setup';
import { generateRandomStringWithSpaces, generateRandomUrl } from '../../../utils/form';
import { generateName } from '../../../utils/faker';


test.describe('Magic AI Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/magic_ai');
    });

    /**
     * Update the Open AI General Settings.
     */
    test('should update openAI credential', async ({ adminPage }) => {
        await adminPage.locator('label > div').first().click();
        await adminPage.locator('input[name="general[magic_ai][settings][api_key]"]').fill(generateRandomStringWithSpaces(100));
        await adminPage.locator('input[name="general[magic_ai][settings][organization]"]').fill(generateName());
        await adminPage.locator('input[name="general[magic_ai][settings][api_domain]"]').fill(generateRandomUrl());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Open AI General Settings.
     */
    test('should mange content using AI', async ({ adminPage }) => {
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').click();
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][product_short_description_prompt]"]').fill(generateRandomStringWithSpaces(50));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][product_description_prompt]"]').fill(generateRandomStringWithSpaces(60));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][category_description_prompt]"]').fill(generateRandomStringWithSpaces(70));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][cms_page_content_prompt]"]').fill(generateRandomStringWithSpaces(50));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
