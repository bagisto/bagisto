import { test, expect } from '../../../setup';
import { generateRandomStringWithSpaces, generateRandomUrl } from '../../../utils/form';
import { generateName, generateDescription } from '../../../utils/faker';


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
        await adminPage.click('label[for="general[magic_ai][settings][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="general[magic_ai][settings][enabled]"]');
        
        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();

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
     * Update the Open AI Content Generation.
     */
    test('should mange content using AI', async ({ adminPage }) => {
        await adminPage.click('label[for="general[magic_ai][content_generation][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="general[magic_ai][content_generation][enabled]"]');

        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();

        await adminPage.locator('textarea[name="general[magic_ai][content_generation][product_short_description_prompt]"]').fill(generateDescription(100));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][product_description_prompt]"]').fill(generateDescription(100));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][category_description_prompt]"]').fill(generateDescription(100));
        await adminPage.locator('textarea[name="general[magic_ai][content_generation][cms_page_content_prompt]"]').fill(generateDescription(100));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Open AI Image Generation.
     */
    test('should enable image generation using AI', async ({ adminPage }) => {
        await adminPage.click('label[for="general[magic_ai][image_generation][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="general[magic_ai][image_generation][enabled]"]');

        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Open AI Review Translation.
     */
    test('should enable review translation using AI', async ({ adminPage }) => {
        await adminPage.click('label[for="general[magic_ai][review_translation][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="general[magic_ai][review_translation][enabled]"]');

        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();
        await adminPage.selectOption('select[name="general[magic_ai][review_translation][model]"]', 'llama2');
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Open AI Review Translation.
     */
    test('should craft a personalized checkout message for customers usin AI', async ({ adminPage }) => {
        await adminPage.click('label[for="general[magic_ai][checkout_message][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="general[magic_ai][review_translation][enabled]"]');

        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();
        await adminPage.selectOption('select[name="general[magic_ai][checkout_message][model]"]', 'llama2');
        const searchEngine = adminPage.locator('select[name="general[magic_ai][checkout_message][model]"]');
        await expect(searchEngine).toHaveValue('llama2');

        await adminPage.locator('textarea[name="general[magic_ai][checkout_message][prompt]"]').fill(generateDescription(100));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
