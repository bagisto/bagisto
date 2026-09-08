import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { AdminOrderPage } from "../../pages/admin/sales/AdminOrderPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import { SimpleProductCheckout } from "../../pages/shop/checkout/product-types/SimpleProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";
import { FLAT_RATE, formatPrice } from "../../utils/prices";

const PRICE = 199;

test.describe("simple product checkout", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `simple-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: PRICE,
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

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName);

        await new OrderPage(shopPage).expectOrderListed(
            orderId,
            "Pending",
            formatPrice(PRICE),
        );
    });

    test("should place an order as a guest", async ({ adminPage, shopPage }) => {
        const checkout = new SimpleProductCheckout(shopPage);

        const orderId = await checkout.checkout(productName, { address: "guest" });

        await new AdminOrderPage(adminPage).expectStatus(orderId, "Pending");
    });

    test("should place an order with a new address entered at checkout", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName, {
            address: "new",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });

    test("should add the flat rate to the total when flat rate shipping is chosen", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
        });

        await new OrderPage(shopPage).expectOrderListed(
            orderId,
            "Pending",
            formatPrice(PRICE + FLAT_RATE),
        );
    });

    test("should place an order with cash on delivery", async ({ shopPage }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName, {
            shipping: "flatrate",
            payment: "cashondelivery",
        });

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });
});
