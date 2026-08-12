import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("rental booking product checkout flow", () => {
    test.describe("rental booking product for hourly and daily basis with available every week and same slot all days", () => {
        test("should create rental booking product for hourly and daily basis with available every week and same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalcheckoutHourlyDaily(false);
        });

        test("should create rental booking product for hourly and daily basis with available every week and same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                true,
                "10",
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                false,
                undefined,
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with available every week and not same slot all days", () => {
        test("should create rental booking product for daily and hourly basis with available every week not same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalcheckoutHourlyDaily(false);
        });

        test("should create rental booking product for daily and hourly basis with available every week not same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                true,
                "10",
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                false,
                undefined,
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with not available every week and same slot all days", () => {
        test("should create rental booking product for daily and hourly basis with not available every week and same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
        });
        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalcheckoutHourlyDaily(false);
        });

        test("should create rental booking product for daily and hourly basis with not available every week and same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                true,
                "10",
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                false,
                undefined,
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with not available every week and not same slot all days", () => {
        test("should create rental booking product for daily and hourly basis with not available every week and not same slot all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalcheckoutHourlyDaily(false);
        });

        test("should create rental booking product for daily and hourly basis with not available every week and not same slot all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `rental-both-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                true,
                "10",
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalcheckoutHourlyDaily(
                false,
                undefined,
                false,
            );
            await checkout.verifyCancellationNotAllowed(id);
        });
    });
});
