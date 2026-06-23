import { test, expect } from "../setup";
import {
    generateName,
    generateDescription,
    generatePhoneNumber,
} from "../utils/faker";
import { ProductCreation } from "../pages/admin/catalog/products/ProductCreatePage";
import { CartPage } from "../pages/shop/CartPage";
import { loginAsAdmin } from "../utils/admin";

const CART_WAITING_TIME = 2000;

test.describe("cart management", () => {
    test.beforeAll(async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "simple",
            sku: `SKU-${Date.now()}`,
            name: `Simple-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should increase the quantity from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();

        await cartPage.increaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);

        await cartPage.increaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);
    });

    test("should decrease the quantity from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.openMiniCart();

        await cartPage.increaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);
        await cartPage.increaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);

        await cartPage.decreaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);
        await cartPage.decreaseQuantityFromMiniCart();
        await shopPage.waitForTimeout(CART_WAITING_TIME);
    });

    test("should remove the product from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.openMiniCart();
        await cartPage.removeProductFromMiniCart();

        await expect(
            shopPage
                .getByText("Item is successfully removed from the cart.")
                .first(),
        ).toBeVisible();
    });

    test("should add product to cart", async ({ shopPage }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
    });

    test("should add different product to cart and update quantity from cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.increaseQuantityFromCartView();
        await cartPage.updateCart();

        await expect(
            shopPage.getByText("Quantity updated successfully").first(),
        ).toBeVisible();
    });

    test("should add product to cart and decrement the quantity of product from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.increaseQuantityFromCartView();
        await cartPage.decreaseQuantityFromCartView();
        await cartPage.updateCart();

        await expect(
            shopPage.getByText("Quantity updated successfully").first(),
        ).toBeVisible();
    });

    test("should remove product from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await shopPage.waitForTimeout(CART_WAITING_TIME);

        await cartPage.goToCartView();
        await cartPage.removeProductFromCartView();

        await expect(
            shopPage
                .getByText("Item is successfully removed from the cart.")
                .first(),
        ).toBeVisible();
        await shopPage.waitForTimeout(CART_WAITING_TIME);
    });

    test("should remove all products from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.removeAllFromCartView();

        await expect(
            shopPage
                .getByText("Selected items successfully removed from cart.")
                .first(),
        ).toBeVisible();
    });

    test("should apply coupon", async ({ adminPage, shopPage }) => {
        const cartPage = new CartPage(shopPage);
        const couponCode = generatePhoneNumber();

        await adminPage.goto("admin/marketing/promotions/cart-rules");
        await adminPage.waitForSelector(
            'a.primary-button:has-text("Create Cart Rule")',
        );
        await adminPage.click('a.primary-button:has-text("Create Cart Rule")');

        await adminPage.waitForSelector(
            'form[action*="/promotions/cart-rules/create"]',
        );

        await adminPage.fill("#name", generateName());
        await adminPage.fill("#description", generateDescription());
        await adminPage.locator("#coupon_type").selectOption("1");
        await adminPage.locator("#use_auto_generation").selectOption("0");
        await adminPage
            .getByRole("textbox", { name: "Coupon Code" })
            .fill(couponCode);
        await adminPage
            .getByRole("textbox", { name: "Uses Per Coupon" })
            .fill("100");
        await adminPage
            .getByRole("textbox", { name: "Uses Per Customer" })
            .fill("100");

        await adminPage.click('div.secondary-button:has-text("Add Condition")');
        await adminPage.waitForSelector(
            'select[id="conditions\\[0\\]\\[attribute\\]"]',
        );
        await adminPage
            .locator('[id="conditions\\[0\\]\\[attribute\\]"]')
            .selectOption("cart_item|quantity");
        await adminPage
            .locator('select[name="conditions\\[0\\]\\[operator\\]"]')
            .selectOption(">=");
        await adminPage
            .locator('input[name="conditions\\[0\\]\\[value\\]"]')
            .fill("1");
        await adminPage.locator("#action_type").selectOption("by_fixed");
        await adminPage.fill('input[name="discount_amount"]', "10");
        await adminPage.fill('input[name="sort_order"]', "1");
        await adminPage.click('label[for="channel__1"]');
        await expect(adminPage.locator("input#channel__1")).toBeChecked();
        await adminPage.locator("#customer_group__1").nth(1).click();
        await adminPage.click('label[for="customer_group__2"]');
        await expect(
            adminPage.locator("input#customer_group__2"),
        ).toBeChecked();
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();
        await adminPage.click(
            'button.primary-button:has-text("Save Cart Rule")',
        );
        await expect(adminPage.locator("#app")).toContainText(
            "Cart rule created successfully",
        );

        await cartPage.gotoHome();
        await cartPage.searchProduct("simple");
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.goToCartView();
        await cartPage.applyCoupon(couponCode);
        await cartPage.expectCouponApplied();
    });
});
