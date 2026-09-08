import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { BookingsAdminPage } from "../../pages/admin/sales/BookingsAdminPage";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("event booking product checkout flow", () => {
    let createdProducts: string[];

    test.beforeEach(() => {
        createdProducts = [];
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent(createdProducts);
    });

    test.describe("event booking product for one ticket", () => {
        test("should allow customer to complete checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `event-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "12", tickets: 1 });
            await new BookingsAdminPage(adminPage).expectDayBooking(customer, id);
        });

        test("should allow customer to complete checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `event-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "12", tickets: 1, allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("event booking product for multiple tickets", () => {
        test("should allow customer to complete checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `event-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                numberOfTickets: 2,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout(product.name, { hour: "12", tickets: 2 });
            await new BookingsAdminPage(adminPage).expectDayBooking(customer, id);
        });

        test("should allow customer to complete checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "event",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `event-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                numberOfTickets: 2,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout(product.name, { hour: "12", tickets: 2, allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });
});
