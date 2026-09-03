import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";
import { CheckoutConfigurationPage } from "../../../pages/admin/configuration/sales/CheckoutConfigurationPage";

const SHOPPING_CART_SETTINGS = [
    "sales[checkout][shopping_cart][allow_guest_checkout]",
    "sales[checkout][shopping_cart][cart_page]",
    "sales[checkout][shopping_cart][cross_sell]",
    "sales[checkout][shopping_cart][estimate_shipping]",
];

test.describe("checkout configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await new CheckoutConfigurationPage(adminPage).open();
    });

    test("should enable guest checkout, cart page, cross-sell products, and estimated shipping", async ({
        adminPage,
    }) => {
        const page = new CheckoutConfigurationPage(adminPage);

        await page.enableShoppingCartSettings(SHOPPING_CART_SETTINGS);
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
