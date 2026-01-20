// import { test, expect } from '../../../setup';
// import { generateDescription } from '../../../utils/faker';

// test.describe('Checkout Configuration', () => {
//     /**
//      * Navigate to the configuration page.
//      */
//     test.beforeEach(async ({ adminPage }) => {
//         await adminPage.goto('admin/configuration/sales/checkout');
//     });

//     /**
//      * Update Shopping Cart Configuration.
//      */
//     test('should enable guest checkout, cart page, cross-sell products, and estimated shipping', async ({ adminPage }) => {
//         await adminPage.click('label[for="sales[checkout][shopping_cart][allow_guest_checkout]"]');
//         const guestCheckoutToggle = await adminPage.locator('input[name="sales[checkout][shopping_cart][allow_guest_checkout]"]');
//         // await expect(guestCheckoutToggle).toBeChecked();

//         await adminPage.click('label[for="sales[checkout][shopping_cart][cart_page]"]');
//         const cartPageToggle = await adminPage.locator('input[name="sales[checkout][shopping_cart][cart_page]"]');
//         // await expect(cartPageToggle).toBeChecked();

//         await adminPage.click('label[for="sales[checkout][shopping_cart][cross_sell]"]');
//         const crossSellProductToggle = await adminPage.locator('input[name="sales[checkout][shopping_cart][cross_sell]"]');
//         // await expect(crossSellProductToggle).toBeChecked();

//         await adminPage.click('label[for="sales[checkout][shopping_cart][estimate_shipping]"]');
//         const estimatedShipping = await adminPage.locator('input[name="sales[checkout][shopping_cart][estimate_shipping]"]');
//         // await expect(estimatedShipping).toBeChecked();

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });

//     /**
//      * Update My Cart Configuration.
//      */
//     test('should enable settings show a summary of item quantities and display the total number of items', async ({ adminPage }) => {
//         await adminPage.selectOption('select[name="sales[checkout][my_cart][summary]"]', 'display_item_quantity');
//         const sort = adminPage.locator('select[name="sales[checkout][my_cart][summary]"]');
//         await expect(sort).toHaveValue('display_item_quantity');

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });

//     /**
//      * Update Mini Cart Configuration.
//      */
//     test('should enable mini cart settings to display the mini cart', async ({ adminPage }) => {
//         await adminPage.click('label[for="sales[checkout][mini_cart][display_mini_cart]"]');
//         const miniCart = await adminPage.locator('input[name="sales[checkout][mini_cart][display_mini_cart]"]');
//         // await expect(miniCart).toBeChecked();

//         await adminPage.fill('input[name="sales[checkout][mini_cart][offer_info]"]', generateDescription(100));

//         await adminPage.click('button[type="submit"].primary-button:visible');

//         /**
//          * Verify the change is saved.
//          */
//         await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
//     });
// });