import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products";
import { SimpleProductCheckout } from "../../pages/shop/checkout/product-types/SimpleProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * =============================
 * SIMPLE PRODUCT CHECKOUT FLOW
 * =============================
 * This test suite covers:
 * 1. Creating a simple product from the admin panel.
 * 2. Completing checkout for the simple product.
 */
test.describe("simple product checkout flow", () => {
    test("should create simple product", async ({ adminPage }) => {
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

    test("should allow customer to complete checkout for simple product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.checkoutWithDefaultShipping();
    });

    test("should allow guest to complete checkout for simple product successfully", async ({
        shopPage,
    }) => {
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.guestCheckout();
    });

    test("should use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.checkoutWithDefaultShipping();
    });

    test("should not use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.checkoutWithNewAddress();
    });

    test("should allow customer to complete checkout for simple product via flat rate shipping successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.checkoutWithFlatRateShipping();
    });

    test("should allow customer to complete checkout for simple product via cash on delivery successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new SimpleProductCheckout(shopPage);
        await checkout.checkoutWithCOD();
    });
});
