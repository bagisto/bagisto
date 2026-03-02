import { test, expect } from "../../../setup";
import { generateDescription } from "../../../utils/faker";

const CHECKOUT_CONFIG_URL = "admin/configuration/sales/checkout";
const SAVE_BUTTON = 'button[type="submit"].primary-button:visible';
const SUCCESS_MESSAGE = "Configuration saved successfully";
const SHOPPING_CART_TOGGLES = [
    'label[for="sales[checkout][shopping_cart][allow_guest_checkout]"]',
    'label[for="sales[checkout][shopping_cart][cart_page]"]',
    'label[for="sales[checkout][shopping_cart][cross_sell]"]',
    'label[for="sales[checkout][shopping_cart][estimate_shipping]"]',
];

async function saveAndAssertConfiguration(adminPage) {
    await adminPage.click(SAVE_BUTTON);
    await expect(adminPage.getByText(SUCCESS_MESSAGE).first()).toBeVisible();
}

test.describe("Checkout Configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto(CHECKOUT_CONFIG_URL);
    });

    test("should enable guest checkout, cart page, cross-sell products, and estimated shipping", async ({
        adminPage,
    }) => {
        for (const toggle of SHOPPING_CART_TOGGLES) {
            await adminPage.click(toggle);
        }
        await saveAndAssertConfiguration(adminPage);
    });

    test("should enable settings show a summary of item quantities and display the total number of items", async ({
        adminPage,
    }) => {
        await adminPage.selectOption(
            'select[name="sales[checkout][my_cart][summary]"]',
            "display_item_quantity",
        );
        const sort = adminPage.locator(
            'select[name="sales[checkout][my_cart][summary]"]',
        );
        await expect(sort).toHaveValue("display_item_quantity");
        await saveAndAssertConfiguration(adminPage);
    });

    test("should enable mini cart settings to display the mini cart", async ({
        adminPage,
    }) => {
        await adminPage.click(
            'label[for="sales[checkout][mini_cart][display_mini_cart]"]',
        );
        await adminPage.fill(
            'input[name="sales[checkout][mini_cart][offer_info]"]',
            generateDescription(100),
        );
        await saveAndAssertConfiguration(adminPage);
    });
});
