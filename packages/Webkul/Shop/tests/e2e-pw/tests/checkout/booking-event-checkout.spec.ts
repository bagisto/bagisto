import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("event booking product checkout flow", () => {
    test.describe("Event Booking product for one ticket ", () => {
        test("Should create event booking product for one ticket", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `event-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12", 1);
            await checkout.verifyduration(customer, id, false);
        });

        test("Should create event booking product for one ticket without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `event-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12", 1, false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("event Booking product for multiple tickets ", () => {
        test("should create event booking product with multiple tickets", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `event-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                numberOfTickets: 2,
            });
        });

        test("should allow customer to complete checkout", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12", 2);
            await checkout.verifyduration(customer, id, false);
        });

        test("Should create event booking product with multiple tickets without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `event-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                numberOfTickets: 2,
            });
        });

        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12", 2, false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });
});
