import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products";
import { ConfigurableProductCheckout } from "../../pages/shop/checkout/product-types/ConfigurableProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ===================================
 * CONFIGURABLE PRODUCT CHECKOUT FLOW
 * ===================================
 * This test suite covers:
 * 1. Creating a configurable product with variations.
 * 2. Completing checkout for the configurable product.
 */
test.describe("configurable product checkout flow", () => {
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

    test("should allow customer to complete checkout for configurable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.checkoutWithDefaultShipping();
    });

    test("should allow guest to complete checkout for configurable product successfully", async ({
        shopPage,
    }) => {
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.guestCheckout();
    });

    test("should use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.checkoutWithDefaultShipping();
    });

    test("should not use same address for shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.checkoutWithNewAddress();
    });
    
    test("should allow customer to complete checkout for configurable product via flat rate shipping successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.checkoutWithFlatRateShipping();
    });

    test("should allow customer to complete checkout for configurable product via cash on delivery successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new ConfigurableProductCheckout(shopPage);
        await checkout.checkoutWithCOD();
    });
});
