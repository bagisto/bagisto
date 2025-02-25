import { test, expect } from '../../../setup';

test.describe('Rich Snippets Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/catalog/rich_snippets');
    });

    /**
     * Update the Rich Snippets Configuration.
     */
    test('should update products settings including with sku, weight, categories, images, reviews, ratings, offers and etc.', async ({ adminPage }) => {
        await adminPage.click('label[for="catalog[rich_snippets][products][enable]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][enable]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_sku]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_sku]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_weight]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_weight]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_categories]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_categories]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_images]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_images]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_reviews]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_reviews]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_ratings]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_ratings]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[rich_snippets][products][show_offers]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[rich_snippets][products][show_offers]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
