import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ==============================
 * VIRTUAL PRODUCT CHECKOUT FLOW
 * ==============================
 * This test suite covers:
 * 1. Creating a virtual product (no shipping required).
 * 2. Completing checkout for the virtual product.
 */
test.describe("virtual product checkout flow", () => {
    test("should create virtual product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "virtual",
            sku: `SKU-${Date.now()}`,
            name: `virtual-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should allow customer to complete checkout for virtual product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.virtualCheckout();
    });
});
