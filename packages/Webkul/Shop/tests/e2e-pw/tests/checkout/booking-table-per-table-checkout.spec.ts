import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { BookingsAdminPage } from "../../pages/admin/sales/BookingsAdminPage";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("table booking product checkout flow", () => {
    let createdProducts: string[];

    test.beforeEach(() => {
        createdProducts = [];
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent(createdProducts);
    });

    test.describe("per_table | every week | same slot all days", () => {
        test("should allow customer to complete checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10");
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("per_table | every week | different slots", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10");
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: true,
                sameSlotAllDays: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("per_table | date range | same slot all days", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10");
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("per_table | date range | different slots", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10");
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "table",
                tableType: "per_table",
                availableEveryWeek: false,
                sameSlotAllDays: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `table-per-table-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.tableCheckout(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });
});
