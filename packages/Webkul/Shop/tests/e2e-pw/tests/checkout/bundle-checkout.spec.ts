import { expect, test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import { BundleProductCheckout } from "../../pages/shop/checkout/product-types/BundleProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("bundle product checkout", () => {
    let productName: string;
    let created: string[];
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        const productCreation = new ProductCreatePage(adminPage);
        productListPage = new ProductListPage(adminPage);
        created = [];

        const items = [`Simple-${uniqueStamp()}`, `Simple-${uniqueStamp()}`];

        for (const item of items) {
            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name: item,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
            created.push(item);
        }

        productName = `bundle-${uniqueStamp()}`;

        await productCreation.createProduct({
            type: "bundle",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
            bundleItems: items,
        });
        created.unshift(productName);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent(created);
    });

    test("should place an order with free shipping and money transfer for a signed in customer", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new BundleProductCheckout(shopPage).checkout(productName);

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should place an order as a guest", async ({ shopPage }) => {
        const orderId = await new BundleProductCheckout(shopPage).checkout(productName, {
            address: "guest",
        });

        expect(orderId).toMatch(/^\d+$/);
    });

    test("should place an order with flat rate shipping", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new BundleProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should place an order with cash on delivery", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new BundleProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
            payment: "cashondelivery",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });
});
