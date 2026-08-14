import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("rental booking product checkout flow", () => {
    test.describe("rental booking product for hourly basis with available every week with same slot all days ", () => {
        test("should create rental booking product for hourly basis with available every week with same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: true,
            });
        });
        test("should allow customer to complete checkout ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10");
        });

        test("should create rental booking product for hourly basis with available every week with same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: true,
            });
        });
        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly basis with available every week and not same slot all days", () => {
        test("should create rental booking product for hourly basis with available every week and not same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10");
        });

        test("should create rental booking product for hourly basis with available every week and not same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly basis without available every week and same slot all days", () => {
        test("should create rental booking product for hourly basis without available every week and same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: true,
            });
        });

        test("should allow customer to complete checkout ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10");
        });

        test("should create rental booking product for hourly basis without available every week and same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: true,
            });
        });

        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly basis without available every week and not same slot all days", () => {
        test("should create rental booking product for hourly basis without available every week and not same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10");
        });

        test("should create rental booking product for hourly basis without available every week and not same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-hourly-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "hourly",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourly("10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });
});
