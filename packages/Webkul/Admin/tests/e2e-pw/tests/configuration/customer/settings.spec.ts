import { test, expect } from '../../../setup';
import { generateRandomStringWithSpaces } from '../../../utils/form';


test.describe('Settings Configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/customer/settings');
    });

    /**
     * Update the Wishlist Configuration.
     */
    test('should enable the wishlist feature', async ({ adminPage }) => {
        await adminPage.click('label[for="customer[settings][wishlist][wishlist_option]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][wishlist][wishlist_option]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update Redirect Page Option Configuration.
     */
    test('should update the redirect page option after the login', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="customer[settings][login_options][redirected_to_page]"]', 'home');
        const weightUnitSelect = adminPage.locator('select[name="customer[settings][login_options][redirected_to_page]"]');
        await expect(weightUnitSelect).toHaveValue('home');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update Default Customer Group and Newsletter Configuration During Signup.
     */
    test('should update default customer group and enabling the newsletter subscription option during sign-up', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="customer[settings][create_new_account_options][default_group]"]', 'general');
        const defaultGroup = adminPage.locator('select[name="customer[settings][create_new_account_options][default_group]"]');
        await expect(defaultGroup).toHaveValue('general');

        await adminPage.click('label[for="customer[settings][create_new_account_options][news_letter]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][create_new_account_options][news_letter]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update Newsletter Configuration for Footer Section.
     */
    test('should update the newsletter subscription option', async ({ adminPage }) => {
        await adminPage.click('label[for="customer[settings][newsletter][subscription]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][newsletter][subscription]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
    
    /**
     * Update Newsletter Configuration for Footer Section.
     */
    test('should enable the social login option', async ({ adminPage }) => {
        await adminPage.click('label[for="customer[settings][social_login][enable_facebook]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][social_login][enable_facebook]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[settings][social_login][enable_twitter]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][social_login][enable_twitter]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[settings][social_login][enable_google]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][social_login][enable_google]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[settings][social_login][enable_linkedin-openid]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][social_login][enable_linkedin-openid]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('label[for="customer[settings][social_login][enable_github]"]');
        // const toggleInput = await adminPage.locator('input[name="customer[settings][social_login][enable_github]"]');
        // await expect(toggleInput).toBeChecked();

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});
