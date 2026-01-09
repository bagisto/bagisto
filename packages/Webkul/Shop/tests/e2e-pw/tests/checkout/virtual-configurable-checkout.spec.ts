import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { MultipleCheckout } from "../../pages/multiple-checkout";

/**
 * ===============================
 * Multiple PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Checkout of multiple products
 */
test.describe("should complete multiple product checkout flow", () => {
    /**
     * Admin creates a Virtual Product
     */
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
    /**
     * Admin creates a Configurable Product
     */
    test("should create configurable product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createConfigProduct({
            type: "configurable",
            sku: `SKU-${Date.now()}`,
            name: `Config-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    /**
     * completes checkout of multiple products successfully
     * Virtual and Configure
     */
    test("should allow customer to complete checkout for virtual and configurable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndConfig();
    });
});
