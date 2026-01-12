import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ============================
 * GROUP PRODUCT CHECKOUT FLOW
 * ============================
 * This test suite covers:
 * 1. Creating a simple product to be associated with a group product.
 * 2. Creating a grouped product.
 * 3. Completing checkout for the grouped product.
 */
test.describe("group product checkout flow", () => {
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

    test("should allow customer to complete checkout for group product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.groupCheckout();
    });

    test("should allow guest to complete checkout for group product successfully", async ({
        shopPage,
    }) => {
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.guestCheckoutGroup();
    });

    test("should allow customer to complete checkout for group product via flat rate shipping successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.groupCheckoutFlatRate();
    });

    test("should allow customer to complete checkout for group product via cash on delivery successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.groupCheckoutCOD();
    });
});
