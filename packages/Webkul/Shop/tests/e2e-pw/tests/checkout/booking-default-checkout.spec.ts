import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { BookingsAdminPage } from "../../pages/admin/sales/BookingsAdminPage";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("default booking product checkout flow", () => {
    let createdProducts: string[];

    test.beforeEach(() => {
        createdProducts = [];
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent(createdProducts);
    });

    test.describe("one booking for many days", () => {
        test("should allow customer to complete checkout for one booking for many days", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "default",
                defaultBookingType: "one",
                sku: `SKU-${uniqueStamp()}`,
                name: `default-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
            createdProducts.push(product.name);

            const customer = await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout(product.name, { hour: "12" });
            await new BookingsAdminPage(adminPage).expectDayBooking(customer, id);
        });

        test("should prevent cancellation when toggle is off in customer end", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "default",
                defaultBookingType: "one",
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `default-no-cancel-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.checkout(product.name, { hour: "12", allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });

        test.describe("many bookings for one day", () => {
            test("should allow customer to complete checkout for many bookings for one day", async ({
                adminPage,
                shopPage,
            }) => {
                const product = await new ProductCreatePage(adminPage).createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "many",
                    sku: `SKU-${uniqueStamp()}`,
                    name: `default-${uniqueStamp()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
                createdProducts.push(product.name);

                const customer = await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout(product.name, { hour: "10" });
                await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
            });

            test("should prevent cancellation when toggle is off in customer end", async ({
                adminPage,
                shopPage,
            }) => {
                const product = await new ProductCreatePage(adminPage).createProduct({
                    type: "booking",
                    bookingType: "default",
                    defaultBookingType: "many",
                    allowCancellation: false,
                    sku: `SKU-${uniqueStamp()}`,
                    name: `default-${uniqueStamp()}`,
                    shortDescription: "Short desc",
                    description: "Full desc",
                    price: 199,
                    weight: 1,
                    inventory: 100,
                });
                createdProducts.push(product.name);

                await loginAsCustomer(shopPage);
                await addAddress(shopPage);
                const checkout = new BookingProductCheckout(shopPage);
                const id = await checkout.checkout(product.name, { hour: "10", allowCancellation: false });
                await checkout.expectCancellationNotAllowedOnOrder(id);
            });
        });
    });
});
