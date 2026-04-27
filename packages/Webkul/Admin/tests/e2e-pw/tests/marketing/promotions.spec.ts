import { test, expect } from '../../setup';
import { generateName, generateDescription } from '../../utils/faker';

test.describe('promotion management', () => {
    test.describe('cart rule management', () => {
        test('should create a cart rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/cart-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Cart Rule")');
            await adminPage.click('a.primary-button:has-text("Create Cart Rule")');
            await adminPage.waitForSelector('form[action*="/promotions/cart-rules/create"]');
            await adminPage.fill('#name', generateName());
            await adminPage.fill('#description', generateDescription());
            await adminPage.click('div.secondary-button:has-text("Add Condition")');
            await adminPage.selectOption('select[id="conditions[0][attribute]"]', 'product|name');
            await adminPage.waitForSelector('input[name="conditions[0][value]"]');
            await adminPage.fill('input[name="conditions[0][value]"]', generateName());
            await adminPage.fill('input[name="discount_amount"]', '10');
            await adminPage.fill('input[name="sort_order"]', '1');
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();
            await adminPage.click('label[for="customer_group__2"]');
            await expect(adminPage.locator("input#customer_group__2")).toBeChecked();
            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();
            await adminPage.click('button.primary-button:has-text("Save Cart Rule")');

            await expect(adminPage.getByText('Cart rule created successfully').first()).toBeVisible();
        });

        test('should edit a cart rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/cart-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Cart Rule")');
            await adminPage.waitForSelector('span.cursor-pointer.icon-edit', { state: 'visible' });
            const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
            await iconEdit[0].click();
            await adminPage.waitForSelector('form[action*="/promotions/cart-rules/edit"]');
            await adminPage.click('button.primary-button:has-text("Save Cart Rule")');

            await expect(adminPage.getByText('Cart rule updated successfully').first()).toBeVisible();
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

            await expect(adminPage.getByText('Cart Rule Deleted Successfully').first()).toBeVisible();
        });
    });

    test.describe('catalog rule management', () => {
        test('should create a catalog rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/catalog-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Catalog Rule")');
            await adminPage.click('a.primary-button:has-text("Create Catalog Rule")');
            await adminPage.waitForSelector('form[action*="/promotions/catalog-rules/create"]');
            await adminPage.fill('#name', generateName());
            await adminPage.fill('#description', generateDescription());
            await adminPage.click('div.secondary-button:has-text("Add Condition")');
            await adminPage.waitForTimeout(1000)
            await adminPage.selectOption('select[id="conditions[0][attribute]"]', 'product|name');
            await adminPage.waitForSelector('input[name="conditions[0][value]"]');
            await adminPage.fill('input[name="conditions[0][value]"]', generateName());
            await adminPage.fill('input[name="discount_amount"]', '10');
            await adminPage.fill('input[name="sort_order"]', '1');
            await adminPage.click('label[for="channel__1"]');
            await expect(adminPage.locator("input#channel__1")).toBeChecked();

            await adminPage.click('label[for="customer_group__2"]')
            await expect(adminPage.locator("input#customer_group__2")).toBeChecked();

            await adminPage.click('label[for="status"]');
            const toggleInput = await adminPage.locator('input[name="status"]');
            await expect(toggleInput).toBeChecked();

            await adminPage.click('button.primary-button:has-text("Save Catalog Rule")');
            await expect(adminPage.getByText('Catalog rule created successfully').first()).toBeVisible();
        });

        test('should edit a catalog rule', async ({ adminPage }) => {
            await adminPage.goto('admin/marketing/promotions/catalog-rules');
            await adminPage.waitForSelector('a.primary-button:has-text("Create Catalog Rule")');
            await adminPage.waitForSelector('span.cursor-pointer.icon-edit', { state: 'visible' });
            const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
            await iconEdit[0].click();
            await adminPage.waitForSelector('form[action*="/promotions/catalog-rules/edit"]');
            await adminPage.click('button.primary-button:has-text("Save Catalog Rule")');

            await expect(adminPage.getByText('Catalog rule updated successfully').first()).toBeVisible();
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

            await expect(adminPage.getByText('Catalog rule deleted successfully').first()).toBeVisible();
        });
    });
});
