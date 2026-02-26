import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { MultipleCheckout } from "../../pages/multiple-checkout";

/**
 * ===============================
 * MULTIPLE PRODUCT CHECKOUT FLOW
 * ===============================
 * This test suite covers:
 * 1. Checkout of multiple products combinations like Simple, Configurable, Group, Virtual, Booking and Bundle
 */
test.describe("multiple types product combination checkout flow", () => {
    /**
     * Admin creates a all Products with basic details to checkout
     */
    test("should create simple product to checkout", async ({ adminPage }) => {
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

    test("should create another simple product to add in group to checkout", async ({
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

    test("should create configurable product to checkout", async ({
        adminPage,
    }) => {
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

    test("should create virtual product to checkout", async ({ adminPage }) => {
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

    test("should create group product to checkout", async ({ adminPage }) => {
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

    test("should create bundle product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "bundle",
            sku: `SKU-${Date.now()}`,
            name: `bundle-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should create downloadable product to checkout", async ({
        adminPage,
    }) => {
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
     * completes checkout of multiple products successfully
     * Simple, Config, Booking, Virtual, Group and Bundle
     */
    test("should allow customer to complete checkout for simple, configurable and virtual product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleConfigVirtulGroup();
    });

    test("should allow customer to complete checkout for simple and configurable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleAndConfig();
    });

    test("should allow customer to complete checkout for simple and downloadable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleAndDownloadable();
    });

    test("should allow customer to complete checkout for virtual and configurable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndConfig();
    });

    test("should allow customer to complete checkout for virtual and group product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutVirtualAndGroup();
    });

    test("should allow customer to complete checkout for simple and bundle product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutSimpleAndBundle();
    });

    test("should allow customer to complete checkout for Downloadable and bundle product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutDownloadableAndBundle();
    });

    test("should allow customer to complete checkout for Group and bundle product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const multipleCheckout = new MultipleCheckout(shopPage);
        await multipleCheckout.customerCheckoutGroupAndBundle();
    });
});
