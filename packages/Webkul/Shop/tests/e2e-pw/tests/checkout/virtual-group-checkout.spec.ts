import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
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
     * Admin creates a Grouped Product
     */
    /**
     * Admin creates a Simple Product
     * (used as a child product in the grouped product)
     */
    test("should create simple product to add in group", async ({
        adminPage,
    }) => {
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
    test("should create another simple product to add in group", async ({
        adminPage,
    }) => {
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
     * Admin creates a Grouped Product
     */
    test("should create group product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "grouped",
            sku: `SKU-${Date.now()}`,
            name: `group-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });
    /**
     * completes checkout of multiple products successfully
     * Virtual and Group
     */
    test("should allow customer to complete checkout for virtual and group product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndGroup();
    });
});
