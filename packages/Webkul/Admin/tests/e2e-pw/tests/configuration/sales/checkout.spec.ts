import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";
import { CheckoutConfigurationPage } from "../../../pages/admin/configuration/sales/CheckoutConfigurationPage";

const SHOPPING_CART_TOGGLES = [
    'label[for="sales[checkout][shopping_cart][allow_guest_checkout]"]',
    'label[for="sales[checkout][shopping_cart][cart_page]"]',
    'label[for="sales[checkout][shopping_cart][cross_sell]"]',
    'label[for="sales[checkout][shopping_cart][estimate_shipping]"]',
];

test.describe("Checkout Configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new CheckoutConfigurationPage(adminPage).open();
    });

    test("should enable guest checkout, cart page, cross-sell products, and estimated shipping", async ({
        adminPage,
    }) => {
        const page = new CheckoutConfigurationPage(adminPage);

        await page.toggleShoppingCartSettings(SHOPPING_CART_TOGGLES);
        await page.saveAndVerify();
    });

    test("should enable settings show a summary of item quantities and display the total number of items", async ({
        adminPage,
    }) => {
        const page = new CheckoutConfigurationPage(adminPage);

        await page.setMyCartSummary("display_item_quantity");
        await expect(await page.getMyCartSummaryValue()).toBe(
            "display_item_quantity",
        );
        await page.saveAndVerify();
    });

    test("should enable mini cart settings to display the mini cart", async ({
        adminPage,
    }) => {
        const page = new CheckoutConfigurationPage(adminPage);

        await page.enableMiniCart(generateDescription(100));
        await page.saveAndVerify();
    });
});
