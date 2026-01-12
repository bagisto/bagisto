import { test } from "../../setup";
import { ProductCreation } from "../../pages/product";
import { ProductCheckout } from "../../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../../utils/customer";

/**
 * ==============================
 * BOOKING PRODUCT CHECKOUT FLOW
 * ==============================
 * This test suite covers:
 * 1. Creating a booking product.
 * 2. Selecting booking details and completing checkout.
 */
test.describe("booking product checkout flow", () => {
    test("should create booking product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "booking",
            sku: `SKU-${Date.now()}`,
            name: `booking-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should allow customer to complete checkout for booking product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.bookingCheckout();
    });
});
