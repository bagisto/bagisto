import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("appointment booking product checkout flow", () => {
    test.describe("available every week and same slot for all days", () => {
        test("should create appointment booking with available every week and same slot for all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with all the test ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create appointment booking with available every week and same slot for all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10", undefined, false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("available every week but not same slot for all days", () => {
        test("should create appointment booking with available every week and not same slot for all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: true,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with all the test ", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create appointment booking with available every week and not same slot for all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10", undefined, false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("not available every week but same slot for all days", () => {
        test("should create appointment booking with not available every week and same slot for all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create appointment booking with not available every week and same slot for all days without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10", undefined, false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("not available every week and not same slot for all days", () => {
        test("should create appointment booking with not available every week and not same slot for all days", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout("10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create appointment booking with not available every week and not same slot for all days without cancelation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `appointment-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout without cancellation", async ({
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
