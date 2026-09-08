import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { SearchPage } from "../pages/shop/SearchPage";
import { uniqueStamp } from "../utils/faker";

test.describe("product search", () => {
    let prefix: string;
    let matching: string;
    let other: string;
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        const productCreation = new ProductCreatePage(adminPage);
        productListPage = new ProductListPage(adminPage);
        prefix = `Srch${uniqueStamp()}`;
        matching = `${prefix} Jacket`;
        other = `Other-${uniqueStamp()}`;

        for (const name of [matching, other]) {
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
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([matching, other]);
    });

    test("should show only the products whose name matches the query", async ({
        shopPage,
    }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(prefix);

        await searchPage.expectResultsHeading(prefix);
        await searchPage.expectProductShown(matching);
        await searchPage.expectProductAbsent(other);
        await searchPage.expectProductCount(1);
    });

    test("should show no products for a query nothing matches", async ({ shopPage }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(`nomatch${uniqueStamp()}`);

        await searchPage.expectNoResults();
    });
});
