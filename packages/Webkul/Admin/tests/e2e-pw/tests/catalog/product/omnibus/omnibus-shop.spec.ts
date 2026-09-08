import { test } from "../../../../setup";
import { OmnibusAdminPage } from "../../../../pages/admin/omnibus/OmnibusAdminPage";
import { OmnibusShopPage } from "../../../../pages/shop/OmnibusShopPage";
import { ProductCreatePage } from "../../../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../../../pages/admin/catalog/products/ProductEditPage";
import { generateDescription, generateSKU, uniqueStamp } from "../../../../utils/faker";

test.describe("omnibus price disclosure on the storefront", () => {
    test.setTimeout(240000);

    let omnibusAdmin: OmnibusAdminPage;
    let productEditPage: ProductEditPage;
    let original: boolean;
    let productName: string;

    test.beforeEach(async ({ adminPage }) => {
        omnibusAdmin = new OmnibusAdminPage(adminPage);
        productEditPage = new ProductEditPage(adminPage);
        original = await omnibusAdmin.readEnabled();
        productName = `Omnibus ${uniqueStamp()}`;

        await omnibusAdmin.setEnabled(true);

        await new ProductCreatePage(adminPage).createSimpleProduct({
            name: productName,
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "300",
            weight: "1",
            inventory: "10",
        });
    });

    test.afterEach(async () => {
        await omnibusAdmin.setEnabled(original);
    });

    test("should disclose the lowest price of the last 30 days as the special price changes", async ({
        shopPage,
    }) => {
        const omnibusShop = new OmnibusShopPage(shopPage);

        await productEditPage.setSpecialPrice(productName, "250");
        await omnibusShop.openProductPage(productName);
        await omnibusShop.expectLowestPriceDisclosed("$300.00");

        await productEditPage.setSpecialPrice(productName, "220");
        await omnibusShop.openProductPage(productName);
        await omnibusShop.expectLowestPriceDisclosed("$250.00");

        await productEditPage.setSpecialPrice(productName, "280");
        await omnibusShop.openProductPage(productName);
        await omnibusShop.expectLowestPriceDisclosed("$220.00");
    });

    test("should hide the price disclosure while omnibus is disabled", async ({
        shopPage,
    }) => {
        const omnibusShop = new OmnibusShopPage(shopPage);

        await productEditPage.setSpecialPrice(productName, "250");
        await omnibusShop.openProductPage(productName);
        await omnibusShop.expectLowestPriceDisclosed("$300.00");

        await omnibusAdmin.setEnabled(false);
        await omnibusShop.openProductPage(productName);
        await omnibusShop.expectNoPriceDisclosure();
    });
});
