import { expect, test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import { ConfigurableProductCheckout } from "../../pages/shop/checkout/product-types/ConfigurableProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

test.describe("configurable product checkout", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `Config-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createConfigProduct({
            type: "configurable",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should place an order with free shipping and money transfer for a signed in customer", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new ConfigurableProductCheckout(shopPage).checkout(productName);

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should place an order as a guest", async ({ shopPage }) => {
        const checkout = new ConfigurableProductCheckout(shopPage);

        const orderId = await checkout.checkout(productName, { address: "guest" });

        expect(orderId).toMatch(/^\d+$/);
    });

    test("should place an order with a new address entered at checkout", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new ConfigurableProductCheckout(shopPage).checkout(productName, {
            address: "new",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should add the flat rate to the total when flat rate shipping is chosen", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new ConfigurableProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should place an order with cash on delivery", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new ConfigurableProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
            payment: "cashondelivery",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });
});
