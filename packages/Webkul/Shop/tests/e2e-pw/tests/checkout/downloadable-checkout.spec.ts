import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import { DownloadableProductCheckout } from "../../pages/shop/checkout/product-types/DownloadableProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

const PRICE = 199;

test.describe("downloadable product checkout", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `downloadable-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "downloadable",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: PRICE,
            inventory: 100,
        });
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should place an order for the selected download link without a shipping step", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new DownloadableProductCheckout(shopPage).checkout(productName);

        await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
    });
});
