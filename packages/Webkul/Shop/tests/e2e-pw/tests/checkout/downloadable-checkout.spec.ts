import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ===============================
 * DOWNLOADABLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Creating a downloadable product
 * 2. Completing checkout for the downloadable product
 */
test.describe("should complete downloadable product checkout flow", () => {
    /**
     * Admin creates a Downloadable Product
     */
    test("should create downloadable product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "downloadable",
            sku: `SKU-${Date.now()}`,
            name: `downloadable-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * Customer completes checkout
     * and gains access to downloadable content
     */
    test("should allow customer to complete checkout for downloadable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.downloadableCheckout();
    });
});
