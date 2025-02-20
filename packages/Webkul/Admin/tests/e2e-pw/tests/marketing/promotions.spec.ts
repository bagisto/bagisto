import { test, expect } from '../../setup';
import { generateName, generateDescription } from '../../utils/faker';

test.describe('promotion management', () => {
    test.describe('cart rule management', () => {
        test('should create a cart rule', async ({ adminPage }) => {
            /**
             * Reaching to the create cart rules page.
             */
            await adminPage.goto('admin/marketing/promotions/cart-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Cart Rule")');
            await adminPage.click('a.primary-button:has-text("Create Cart Rule")');

            /**
             * Waiting for the main form to be visible.
             */
            await adminPage.waitForSelector('form[action*="/promotions/cart-rules/create"]');

            /**
             * General Section.
             */
            await adminPage.fill('#name', generateName());
            await adminPage.fill('#description', generateDescription());

            /**
             * Conditions Section.
             */
            await adminPage.click('div.secondary-button:has-text("Add Condition")');

            // Selecting the condition attribute.
            await adminPage.waitForSelector('select[id="conditions[0][attribute]"]');
            await adminPage.selectOption('select[id="conditions[0][attribute]"]', 'product|name');

            // Filling the condition value.
            await adminPage.waitForSelector('input[name="conditions[0][value]"]');
            await adminPage.fill('input[name="conditions[0][value]"]', generateName());

            /**
             * Actions Section.
             */
            await adminPage.fill('input[name="discount_amount"]', '10');

            /**
             * Settings Section.
             */
            await adminPage.fill('input[name="sort_order"]', '1');

            // Selecting the channel and verifying it.
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();

            // Selecting the customer group and verifying it.
            await adminPage.click('label[for="customer_group__2"]');
            await expect(adminPage.locator("input#customer_group__2")).toBeChecked();

            // Clicking the status and verify the toggle state.
            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();

            /**
             * Saving the cart rule.
             */
            await adminPage.click('button.primary-button:has-text("Save Cart Rule")');

            await expect(adminPage.getByText('Cart rule created successfully')).toBeVisible();
        });

        test('should edit a cart rule', async ({ adminPage }) => {
            /**
             * Reaching to the edit cart rules page.
             */
            await adminPage.goto('admin/marketing/promotions/cart-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Cart Rule")');
            await adminPage.waitForSelector('span.cursor-pointer.icon-edit', { state: 'visible' });
            const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
            await iconEdit[0].click();

            /**
             * Waiting for the main form to be visible.
             */
            await adminPage.waitForSelector('form[action*="/promotions/cart-rules/edit"]');

            // Content will be added here. Currently just checking the general save button.

            /**
             * Saving the cart rule.
             */
            await adminPage.click('button.primary-button:has-text("Save Cart Rule")');

            await expect(adminPage.getByText('Cart rule updated successfully')).toBeVisible();
        });

        test('should delete a cart rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/cart-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Cart Rule")');

            await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
            const iconDelete = await adminPage.$$(
                "span.cursor-pointer.icon-delete"
            );
            await iconDelete[0].click();

            await adminPage.waitForSelector("text=Are you sure");
            const agreeButton = await adminPage.locator(
                'button.primary-button:has-text("Agree")'
            );

            if (await agreeButton.isVisible()) {
                await agreeButton.click();
            } else {
                console.error("Agree button not found or not visible.");
            }

            await expect(adminPage.getByText('Cart Rule Deleted Successfully')).toBeVisible();
        });
    });

    test.describe('catalog rule management', () => {
        test('should create a catalog rule', async ({ adminPage }) => {
            /**
             * Reaching to the create catalog rules page.
             */
            await adminPage.goto('admin/marketing/promotions/catalog-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Catalog Rule")');
            await adminPage.click('a.primary-button:has-text("Create Catalog Rule")');

            /**
             * Waiting for the main form to be visible.
             */
            await adminPage.waitForSelector('form[action*="/promotions/catalog-rules/create"]');

            /**
             * General Section.
             */
            await adminPage.fill('#name', generateName());
            await adminPage.fill('#description', generateDescription());

            /**
             * Conditions Section.
             */
            await adminPage.click('div.secondary-button:has-text("Add Condition")');

            // Selecting the condition attribute.
            await adminPage.waitForSelector('select[id="conditions[0][attribute]"]');
            await adminPage.selectOption('select[id="conditions[0][attribute]"]', 'product|name');

            // Filling the condition value.
            await adminPage.waitForSelector('input[name="conditions[0][value]"]');
            await adminPage.fill('input[name="conditions[0][value]"]', generateName());

            /**
             * Actions Section.
             */
            await adminPage.fill('input[name="discount_amount"]', '10');

            /**
             * Settings Section.
             */
            await adminPage.fill('input[name="sort_order"]', '1');

            // Selecting the channel and verifying it.
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();

            // Selecting the customer group and verifying it.
            await adminPage.click('label[for="customer_group__2"]');
            await expect(adminPage.locator("input#customer_group__2")).toBeChecked();

            // Clicking the status and verify the toggle state.
            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();

            /**
             * Saving the catalog rule.
             */
            await adminPage.click('button.primary-button:has-text("Save Catalog Rule")');

            await expect(adminPage.getByText('Catalog rule created successfully')).toBeVisible();
        });

        test('should edit a catalog rule', async ({ adminPage }) => {
            /**
             * Reaching to the create catalog rules page.
             */
            await adminPage.goto('admin/marketing/promotions/catalog-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Catalog Rule")');
            await adminPage.waitForSelector('span.cursor-pointer.icon-edit', { state: 'visible' });
            const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
            await iconEdit[0].click();

            /**
             * Waiting for the main form to be visible.
             */
            await adminPage.waitForSelector('form[action*="/promotions/catalog-rules/edit"]');

            // Content will be added here. Currently just checking the general save button.

            /**
             * Saving the catalog rule.
             */
            await adminPage.click('button.primary-button:has-text("Save Catalog Rule")');

            await expect(adminPage.getByText('Catalog rule updated successfully')).toBeVisible();
        });

        test('should delete a catalog rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/catalog-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Catalog Rule")');

            await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
            const iconDelete = await adminPage.$$(
                "span.cursor-pointer.icon-delete"
            );
            await iconDelete[0].click();

            await adminPage.waitForSelector("text=Are you sure");
            const agreeButton = await adminPage.locator(
                'button.primary-button:has-text("Agree")'
            );

            if (await agreeButton.isVisible()) {
                await agreeButton.click();
            } else {
                console.error("Agree button not found or not visible.");
            }

            await expect(adminPage.getByText('Catalog rule deleted successfully')).toBeVisible();
        });
    });
});
