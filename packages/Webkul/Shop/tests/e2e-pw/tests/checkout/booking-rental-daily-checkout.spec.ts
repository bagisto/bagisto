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

    test.describe("rental booking product for daily basis with available every week", () => {
        test("should allow customer to complete checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-daily-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "daily",
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutDaily(product.name);
        });

        test("should allow customer to complete checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: true,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-daily-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "daily",
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutDaily(product.name, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });

    test.describe("rental booking product for daily basis not available every week", () => {
        test("should allow customer to complete checkout", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-daily-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "daily",
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            await checkout.rentalCheckoutDaily(product.name);
        });

        test("should allow customer to complete checkout without cancellation", async ({
            adminPage,
            shopPage,
        }) => {
            const product = await new ProductCreatePage(adminPage).createProduct({
                type: "booking",
                bookingType: "rental",
                availableEveryWeek: false,
                allowCancellation: false,
                sku: `SKU-${uniqueStamp()}`,
                name: `rental-daily-${uniqueStamp()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 10,
                inventory: 100,
                rentalType: "daily",
            });
            createdProducts.push(product.name);

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);
            const checkout = new BookingProductCheckout(shopPage);
            const id = await checkout.rentalCheckoutDaily(product.name, false);
            await checkout.expectCancellationNotAllowedOnOrder(id);
        });
    });
});
