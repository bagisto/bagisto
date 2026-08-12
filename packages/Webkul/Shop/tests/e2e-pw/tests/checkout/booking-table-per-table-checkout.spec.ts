import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("table Booking product checkout flow", () => {
    test.describe("per_table | every week | same slot all days", () => {
        test("should create table booking product", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: true,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
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
            const id = await checkout.table_checkout(true, "10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create table booking product without cancellation ", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
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
            const id = await checkout.table_checkout(true, "10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("per_table | every week | different slots", () => {
        test("should create table booking product", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create table booking product without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("per_table | date range | same slot all days", () => {
        test("should create table booking product", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: true,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create table booking product without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: true,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });

    test.describe("per_table | date range | different slots", () => {
        test("should create table booking product", async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10");
            await checkout.verifyduration(customer, id, true);
        });

        test("should create table booking product without cancellation", async ({
            adminPage,
        }) => {
            const productCreation = new ProductCreation(adminPage);
            await productCreation.createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: false,
                allowCancellation: false,
                sku: `SKU-${Date.now()}`,
                name: `table-per-table-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            shopPage,
        }) => {
            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.table_checkout(true, "10", false);
            await checkout.verifyCancellationNotAllowed(id);
        });
    });
});
