import { test, expect } from '../../../setup';
import { generateRandomNumericString } from '../../../utils/faker';

test.describe('taxes configuration', () => {
    /**
     * Navigate to the configuration page.
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/sales/taxes');
    });

    /**
     * Update the Tax Categories Configuration.
     */
    test('should update tax categories configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[taxes][categories][shipping]"]', '0');
        const shippingTaxCategory = adminPage.locator('select[name="sales[taxes][categories][shipping]"]');
        await expect(shippingTaxCategory).toHaveValue('0');

        await adminPage.selectOption('select[name="sales[taxes][categories][product]"]', '0');
        const productTaxCategory = adminPage.locator('select[name="sales[taxes][categories][product]"]');
        await expect(productTaxCategory).toHaveValue('0');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
         await expect(adminPage.locator('#app p' , { hasText: 'Configuration saved successfully' })).toBeVisible();
    });

    /**
     * Update the Tax Calculation Settings.
     */
    test('should update tax calculation configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[taxes][calculation][based_on]"]', 'shipping_address');
        const taxBasedOn = adminPage.locator('select[name="sales[taxes][calculation][based_on]"]');
        await expect(taxBasedOn).toHaveValue('shipping_address');

        await adminPage.selectOption('select[name="sales[taxes][calculation][product_prices]"]', 'excluding_tax');
        const productPrice = adminPage.locator('select[name="sales[taxes][calculation][product_prices]"]');
        await expect(productPrice).toHaveValue('excluding_tax');
        
        await adminPage.selectOption('select[name="sales[taxes][calculation][shipping_prices]"]', 'excluding_tax');
        const shippingPrices = adminPage.locator('select[name="sales[taxes][calculation][shipping_prices]"]');
        await expect(shippingPrices).toHaveValue('excluding_tax');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Default Destination Calculation Configuration.
     */
    test('should update default destination calculation configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[taxes][default_destination_calculation][country]"]', 'IN');
        const taxBasedOn = adminPage.locator('select[name="sales[taxes][default_destination_calculation][country]"]');
        await expect(taxBasedOn).toHaveValue('IN');

        await adminPage.selectOption('select[name="sales[taxes][default_destination_calculation][state]"]', 'UP');
        const productPrice = adminPage.locator('select[name="sales[taxes][default_destination_calculation][state]"]');
        await expect(productPrice).toHaveValue('UP');
        
        await adminPage.fill('input[name="sales[taxes][default_destination_calculation][post_code]"]', generateRandomNumericString(6));

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update the Shopping Cart Display Settings.
     */
    test('should update shopping cart display settings configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[taxes][shopping_cart][display_prices]"]', 'excluding_tax');
        const taxBasedOn = adminPage.locator('select[name="sales[taxes][shopping_cart][display_prices]"]');
        await expect(taxBasedOn).toHaveValue('excluding_tax');

        await adminPage.selectOption('select[name="sales[taxes][shopping_cart][display_subtotal]"]', 'excluding_tax');
        const productPrice = adminPage.locator('select[name="sales[taxes][shopping_cart][display_subtotal]"]');
        await expect(productPrice).toHaveValue('excluding_tax');
        
        await adminPage.selectOption('select[name="sales[taxes][shopping_cart][display_shipping_amount]"]', 'excluding_tax');
        const shippingPrices = adminPage.locator('select[name="sales[taxes][shopping_cart][display_shipping_amount]"]');
        await expect(shippingPrices).toHaveValue('excluding_tax');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });

    /**
     * Update Orders, Invoices, Refunds Display Settings.
     */
    test('should update orders, invoices, refunds display settings configuration', async ({ adminPage }) => {
        await adminPage.selectOption('select[name="sales[taxes][sales][display_prices]"]', 'excluding_tax');
        const taxBasedOn = adminPage.locator('select[name="sales[taxes][sales][display_prices]"]');
        await expect(taxBasedOn).toHaveValue('excluding_tax');

        await adminPage.selectOption('select[name="sales[taxes][sales][display_subtotal]"]', 'excluding_tax');
        const productPrice = adminPage.locator('select[name="sales[taxes][sales][display_subtotal]"]');
        await expect(productPrice).toHaveValue('excluding_tax');
        
        await adminPage.selectOption('select[name="sales[taxes][sales][display_shipping_amount]"]', 'excluding_tax');
        const shippingPrices = adminPage.locator('select[name="sales[taxes][sales][display_shipping_amount]"]');
        await expect(shippingPrices).toHaveValue('excluding_tax');

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
    });
});