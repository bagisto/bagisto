import { test, expect } from '../../../setup';
import { getRandomImageFile } from '../../../utils/form';
import { generateDescription, generateRandomNumericString } from '../../../utils/faker';



test.describe('Product Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/catalog/products');
    });

    /**
     * Update the Compare and Image Search option.
     */
    test('should update the compare and image search', async ({ adminPage }) => {
        await adminPage.click('label[for="catalog[products][settings][compare_option]"]');
        await adminPage.click('label[for="catalog[products][settings][image_search]"]');

        // const toggleInput = await adminPage.locator('input[name="catalog[products][settings][compare_option]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][settings][image_search]"]');
        
        // Ensure the state is correctly toggled
        // await expect(toggleInput).toBeChecked();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Database Search.
     */
    test('should set up the search engine for product searches', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="catalog[products][search][engine]"]', 'elastic');
        const searchEngine = adminPage.locator('select[name="catalog[products][search][engine]"]');
        await expect(searchEngine).toHaveValue('elastic');

        await adminPage.selectOption('select[name="catalog[products][search][admin_mode]"]', 'elastic');
        const adminSearchMode = adminPage.locator('select[name="catalog[products][search][admin_mode]"]');
        await expect(adminSearchMode).toHaveValue('elastic');

        await adminPage.selectOption('select[name="catalog[products][search][storefront_mode]"]', 'elastic');
        const storeFrontSearchMode = adminPage.locator('select[name="catalog[products][search][storefront_mode]"]');
        await expect(storeFrontSearchMode).toHaveValue('elastic');

        await adminPage.locator('input[name="catalog[products][search][min_query_length]"]').fill(generateRandomNumericString(2));
        await adminPage.locator('input[name="catalog[products][search][max_query_length]"]').fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Product view page configuration.
     */
    test('should update the product view page configuration', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][product_view_page][no_of_related_products]"]').fill(generateRandomNumericString(2));
        await adminPage.locator('input[name="catalog[products][product_view_page][no_of_up_sells_products]"]').fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Cart view page configuration
     */
    test('should update the cart view page configuration', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][cart_view_page][no_of_cross_sells_products]"]').fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Storefront configuration
     */
    test('should update the store front configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="catalog[products][storefront][mode]"]', 'grid');
        const defaultListMode = adminPage.locator('select[name="catalog[products][storefront][mode]"]');
        await expect(defaultListMode).toHaveValue('grid');

        await adminPage.locator('input[name="catalog[products][storefront][products_per_page]"]').fill(generateRandomNumericString(2));

        await adminPage.selectOption('select[name="catalog[products][storefront][sort_by]"]', 'name-asc');
        const sortBy = adminPage.locator('select[name="catalog[products][storefront][sort_by]"]');
        await expect(sortBy).toHaveValue('name-asc');

        await adminPage.click('label[for="catalog[products][storefront][buy_now_button_display]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][storefront][buy_now_button_display]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Product Small Image Configuration.
     */
    test('should update the small image size and placeholder', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][cache_small_image][width]"]').fill(generateRandomNumericString(3));
        await adminPage.locator('input[name="catalog[products][cache_small_image][height]"]').fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent('filechooser'),
            adminPage.click('label:has-text("Small Image Placeholder")')
        ]);

        await fileChooser.setFiles(getRandomImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage.locator('[id="catalog\\[products\\]\\[cache_small_image\\]\\[url\\]\\[delete\\]"]').nth(1).click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Product Medium Image Configuration.
     */
    test('should update the medium image size and placeholder', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][cache_medium_image][width]"]').fill(generateRandomNumericString(3));
        await adminPage.locator('input[name="catalog[products][cache_medium_image][height]"]').fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent('filechooser'),
            adminPage.click('label:has-text("Medium Image Placeholder")')
        ]);

        await fileChooser.setFiles(getRandomImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage.locator('[id="catalog\\[products\\]\\[cache_medium_image\\]\\[url\\]\\[delete\\]"]').nth(1).click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Product Large Image Configuration.
     */
    test('should update the large image size and placeholder', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][cache_large_image][width]"]').fill(generateRandomNumericString(3));
        await adminPage.locator('input[name="catalog[products][cache_large_image][height]"]').fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent('filechooser'),
            adminPage.click('label:has-text("Large Image Placeholder")')
        ]);

        await fileChooser.setFiles(getRandomImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage.locator('[id="catalog\\[products\\]\\[cache_large_image\\]\\[url\\]\\[delete\\]"]').nth(1).click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Product Review Configuration.
     */
    test('should update the review configuration', async ({ adminPage }) => {
        await adminPage.click('label[for="catalog[products][review][guest_review]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][review][guest_review]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][review][customer_review]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][review][customer_review]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.selectOption('select[name="catalog[products][review][summary]"]', 'star_counts');
        const searchEngine = adminPage.locator('select[name="catalog[products][review][summary]"]');
        await expect(searchEngine).toHaveValue('star_counts');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Allowed Image and File Upload Size Configuration.
     */
    test('should update the allowed image and file upload size', async ({ adminPage }) => {
        await adminPage.locator('input[name="catalog[products][attribute][image_attribute_upload_size]"]').fill(generateRandomNumericString(3));
        await adminPage.locator('input[name="catalog[products][attribute][file_attribute_upload_size]"]').fill(generateRandomNumericString(3));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Social Share Configuration.
     */
    test('should update social share configuration', async ({ adminPage }) => {
        await adminPage.click('label[for="catalog[products][social_share][enabled]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][enabled]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][facebook]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][facebook]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][twitter]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][twitter]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][pinterest]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][pinterest]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][whatsapp]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][whatsapp]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][linkedin]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][linkedin]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="catalog[products][social_share][email]"]');
        // const toggleInput = await adminPage.locator('input[name="catalog[products][social_share][email]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.locator('input[name="catalog[products][social_share][share_message]"]').fill(generateDescription());

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
