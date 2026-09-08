import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { BookingsAdminPage } from "../../pages/admin/sales/BookingsAdminPage";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("appointment booking product checkout flow", () => {
    let createdProducts: string[];

    test.beforeEach(() => {
        createdProducts = [];
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent(createdProducts);
    });

    test.describe.configure({ timeout: 180 * 1000 });

    test.describe("available every week and same slot for all days", () => {
        test("should allow customer to complete checkout appointment booking with all the test", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10" });
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10", allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("available every week but not same slot for all days", () => {
        test("should allow customer to complete checkout appointment booking with all the test", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10" });
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout appointment booking with all the test without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10", allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("not available every week but same slot for all days", () => {
        test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10" });
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout appointment booking with not available every week and same slot for all days for customer checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: true,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10", allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("not available every week and not same slot for all days", () => {
        test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10" });
            await new BookingsAdminPage(adminPage).expectSlotBooking(customer, id);
        });

        test("should allow customer to complete checkout with not available every week and not same slot for all days for customer checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "appointment",
                sameSlotAllDays: false,
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `appointment-${uniqueStamp()}`,
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
            const id = await checkout.checkout(product.name, { hour: "10", allowCancellation: false });
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });
});
