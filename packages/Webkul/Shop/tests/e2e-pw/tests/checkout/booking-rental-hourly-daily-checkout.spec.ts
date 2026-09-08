import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { BookingProductCheckout } from "../../pages/shop/checkout/product-types/BookingProductCheckout";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("rental booking product checkout flow", () => {
    let createdProducts: string[];

    test.beforeEach(() => {
        createdProducts = [];
    });

    test.afterEach(async ({ adminPage }) => {
        await new ProductListPage(adminPage).deleteProductsIfPresent(createdProducts);
    });

    test.describe("rental booking product for hourly and daily basis with available every week and same slot all days", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, false);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, false, undefined, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with available every week and not same slot all days", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, false);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, false, undefined, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with not available every week and same slot all days", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, false);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: true,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, false, undefined, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("rental booking product for hourly and daily basis with not available every week and not same slot all days", () => {
        test("should allow customer to complete checkout for hourly", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10");
        });

        test("should allow customer to complete checkout for daily", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutHourlyOrDaily(product.name, false);
        });

        test("should allow customer to complete checkout for hourly without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, true, "10", false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });

        test("should allow customer to complete checkout for daily without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-both-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "both",
                sameSlotAllDays: false,
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutHourlyOrDaily(product.name, false, undefined, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });
});
