import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import { VirtualProductCheckout } from "../../pages/shop/checkout/product-types/VirtualProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";
import { formatPrice } from "../../utils/prices";

const PRICE = 199;

test.describe("virtual product checkout", () => {
    let productName: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        productListPage = new ProductListPage(adminPage);
        productName = `virtual-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "virtual",
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

    test("should place an order without a shipping step and charge only the product price", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const orderId = await new VirtualProductCheckout(shopPage).checkout(productName);

        await new OrderPage(shopPage).expectOrderListed(
            orderId,
            "Pending",
            formatPrice(PRICE),
        );
    });
});
