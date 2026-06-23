import { test } from "../../setup";
import { ProductCreation } from "../../pages/admin/catalog/products/ProductCreatePage";
import { DownloadableProductCheckout } from "../../pages/shop/checkout/product-types/DownloadableProductCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";

test.describe("downloadable product checkout flow", () => {
    test("should create downloadable product", async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "downloadable",
            sku: `SKU-${Date.now()}`,
            name: `downloadable-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should allow customer to complete checkout for downloadable product successfully", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);
        const checkout = new DownloadableProductCheckout(shopPage);
        await checkout.checkout();
    });
});
