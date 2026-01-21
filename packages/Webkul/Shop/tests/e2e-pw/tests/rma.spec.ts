import { test } from "../setup";
import { ProductCreation } from "../pages/product";
import { ProductCheckout } from "../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { RMACreation } from "../pages/rma";

/**
 * =============================
 * SIMPLE PRODUCT CHECKOUT FLOW
 * =============================
 * This test suite covers:
 * 1. Creating a simple product from the admin panel.
 * 2. Completing checkout for the simple product.
 */
test.describe("should create rma for order", () => {
    test("should create simple product for checkout to create rma", async ({ adminPage }) => {
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

    test("should allow customer to complete checkout and create rma", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();

        const rmaCreation = new RMACreation(shopPage);
        await rmaCreation.adminLogin();
        await rmaCreation.gotoProductPage();
        await rmaCreation.createInvoice();
        await rmaCreation.createRMA();
    });
});
