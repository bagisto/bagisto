import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("default booking product checkout flow ", () => {
    test.describe("One Booking For Many Days", () => {
        test("should create default booking product with one booking for many days with cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "default",
                defaultBookingType: "one",
                sku: `SKU-${Date.now()}`,
                name: `default-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for one booking for many days", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12");
            await checkout.verifyduration(customer, id, false);
        });

        test("should prevent cancellation when toggle is off", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "default",
                defaultBookingType: "one",
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `default-no-cancel-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        });

        test("should prevent cancellation when toggle is off in customer end", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("12", undefined, false);
            await checkout.verifyCancellationNotAllowed(id);
        });

        test.describe("Many Bookings For One Day", () => {
            test("should create default booking product with many bookings for one day with allow cancellation", async ({
                adminPage,
            }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "many",
                    sku: `SKU-${Date.now()}`,
                    name: `default-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
            });

            test("should allow customer to complete checkout for many bookings for one day", async ({
                shopPage,
            }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout("10");
                await checkout.verifyduration(customer, id, true);
            });

            test("should create default booking product with many bookings for one day with not allowed cancellation", async ({
                adminPage,
            }) => {
                const productCreation = new ProductCreation(adminPage);
                await productCreation.createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "many",
                    allowCancellation: false,
                    sku: `SKU-${Date.now()}`,
                    name: `default-${Date.now()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
            });

            test("should prevent cancellation when toggle is off in customer end", async ({
                shopPage,
            }) => {
                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout("10", undefined, false);
                await checkout.verifyCancellationNotAllowed(id);
            });
        });
    });
});
