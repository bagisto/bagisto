import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { AdminOrderPage } from "../pages/admin/sales/AdminOrderPage";
import { RmaCreatePage } from "../pages/shop/RmaCreatePage";
import { SimpleProductCheckout } from "../pages/shop/checkout/product-types/SimpleProductCheckout";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { uniqueStamp } from "../utils/faker";

test.describe("return requests", () => {
    let productListPage: ProductListPage;
    let productName: string;

    async function createProduct(
        adminPage: import("@playwright/test").Page,
        allowRma: boolean,
    ): Promise<void> {
        productName = `Simple-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
            allowRma,
        });
    }

    async function placeInvoicedOrder(
        adminPage: import("@playwright/test").Page,
        shopPage: import("@playwright/test").Page,
    ): Promise<string> {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new SimpleProductCheckout(shopPage).checkout(productName);

        await new AdminOrderPage(adminPage).createInvoice(orderId);

        return orderId;
    }

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should let a customer request a return for an invoiced order item that allows rma", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const rmaPage = new RmaCreatePage(shopPage);

        await rmaPage.requestReturn(orderId);

        await rmaPage.expectRequestListedFor(productName);
    });

    test("should refuse a return quantity above the ordered quantity", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, true);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);
        const rmaPage = new RmaCreatePage(shopPage);

        await rmaPage.attemptReturnWithExcessQuantity(orderId);

        await rmaPage.expectQuantityRejected();
        await rmaPage.expectNoRequestForOrder(orderId);
    });

    test("should not offer an order whose product does not allow rma", async ({
        adminPage,
        shopPage,
    }) => {
        await createProduct(adminPage, false);

        const orderId = await placeInvoicedOrder(adminPage, shopPage);

        await new RmaCreatePage(shopPage).expectOrderNotOffered(orderId);
    });
});
