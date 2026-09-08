import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { ComparePage } from "../pages/shop/ComparePage";
import { setConfigSwitch } from "../utils/admin";
import { uniqueStamp } from "../utils/faker";

test.describe("product comparison", () => {
    let products: string[];
    let productListPage: ProductListPage;
    let comparePage: ComparePage;
    let compareOptionWasEnabled: boolean;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        compareOptionWasEnabled = await setConfigSwitch(
            adminPage,
            "admin/configuration/catalog/products",
            "catalog[products][settings][compare_option]",
            true,
        );

        const productCreation = new ProductCreatePage(adminPage);
        productListPage = new ProductListPage(adminPage);
        comparePage = new ComparePage(shopPage);
        products = [`Compare-${uniqueStamp()}`, `Compare-${uniqueStamp()}`];

        for (const name of products) {
            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        }

        for (const name of products) {
            await comparePage.addToCompareFromListing(name);
        }
    });

    test.afterEach(async ({ adminPage }) => {
        try {
            await productListPage.deleteProductsIfPresent(products);
        } finally {
            await setConfigSwitch(
                adminPage,
                "admin/configuration/catalog/products",
                "catalog[products][settings][compare_option]",
                compareOptionWasEnabled,
            );
        }
    });

    test("should list every added product on the compare page", async () => {
        await comparePage.open();

        for (const name of products) {
            await comparePage.expectProductListed(name);
        }
    });

    test("should remove one product and keep the other", async () => {
        await comparePage.open();
        await comparePage.removeProduct(products[0]);

        await comparePage.expectProductAbsent(products[0]);
        await comparePage.expectProductListed(products[1]);
    });

    test("should remove every product at once", async () => {
        await comparePage.open();
        await comparePage.deleteAll();

        await comparePage.expectEmpty();
    });
});
