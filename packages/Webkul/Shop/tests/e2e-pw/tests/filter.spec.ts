import { expect, test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { SearchPage } from "../pages/shop/SearchPage";
import { uniqueStamp } from "../utils/faker";

test.describe("product sorting", () => {
    let prefix: string;
    let products: { name: string; price: number }[];
    let productListPage: ProductListPage;

    test.beforeEach(async ({ adminPage }) => {
        const productCreation = new ProductCreatePage(adminPage);
        productListPage = new ProductListPage(adminPage);
        prefix = `Sort${uniqueStamp()}`;
        products = [
            { name: `${prefix} Bravo`, price: 250 },
            { name: `${prefix} Alpha`, price: 120 },
            { name: `${prefix} Charlie`, price: 180 },
        ];

        for (const product of products) {
            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name: product.name,
                shortDescription: "Short desc",
                description: "Full desc",
                price: product.price,
                weight: 1,
                inventory: 100,
            });
        }
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent(products.map((p) => p.name));
    });

    test("should sort products from a to z", async ({ shopPage }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(prefix);
        await searchPage.sortBy("From A-Z");

        await expect.poll(() => searchPage.readProductNames()).toEqual(
            [...products.map((p) => p.name)].sort((a, b) => a.localeCompare(b)),
        );
    });

    test("should sort products from z to a", async ({ shopPage }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(prefix);
        await searchPage.sortBy("From Z-A");

        await expect.poll(() => searchPage.readProductNames()).toEqual(
            [...products.map((p) => p.name)].sort((a, b) => b.localeCompare(a)),
        );
    });

    test("should sort the most expensive product first", async ({ shopPage }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(prefix);
        await searchPage.sortBy("Expensive First");

        await expect.poll(() => searchPage.readProductPrices()).toEqual(
            [...products.map((p) => p.price)].sort((a, b) => b - a),
        );
    });

    test("should sort the cheapest product first", async ({ shopPage }) => {
        const searchPage = new SearchPage(shopPage);

        await searchPage.search(prefix);
        await searchPage.sortBy("Cheapest First");

        await expect.poll(() => searchPage.readProductPrices()).toEqual(
            [...products.map((p) => p.price)].sort((a, b) => a - b),
        );
    });
});
