import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ===============================
 * SIMPLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a simple product from the admin panel
 * 2. Completing checkout for the simple product as a customer
 */
test.describe("should complete simple product checkout flow", () => {
    /**
     * Admin creates a Simple Product with basic details
     */
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

    /**
     * Customer logs in and successfully completes checkout
     * for the previously created Simple Product
     */
    test("should allow customer to complete checkout for simple product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();
    });
    test("should allow guest to complete checkout for simple product successfully", async ({
        shopPage,
    }) => {
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.guestCheckoutSimple();
    });
    test("if use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();
    });
    test("if not use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.shippingChangeCheckoutSimple();
    });
});
