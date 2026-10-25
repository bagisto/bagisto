import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { ProductViewConfigurationPage } from "../pages/admin/configuration/ProductViewConfigurationPage";
import { AuthPage } from "../pages/shop/AuthPage";
import { CartPage } from "../pages/shop/CartPage";
import type { BaseProduct } from "../pages/types/product.types";
import { buildCustomerCredentials, register } from "../utils/customer";
import type { CustomerCredentials } from "../utils/customer";
import { uniqueStamp } from "../utils/faker";

const PRICE = 129;

function buildSimpleProduct(): BaseProduct {
    return {
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Merge-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: PRICE,
        weight: 1,
        inventory: 100,
    };
}

test.describe("cart merge on login", () => {
    let productListPage: ProductListPage;
    let configurationPage: ProductViewConfigurationPage;
    let cartPage: CartPage;
    let authPage: AuthPage;
    let credentials: CustomerCredentials;
    let firstProduct: string;
    let secondProduct: string;
    let buyNowWasEnabled: boolean;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        productListPage = new ProductListPage(adminPage);
        configurationPage = new ProductViewConfigurationPage(adminPage);
        cartPage = new CartPage(shopPage);
        authPage = new AuthPage(shopPage);

        buyNowWasEnabled = await configurationPage.setBuyNowButton(true);

        const productCreatePage = new ProductCreatePage(adminPage);

        firstProduct = (
            await productCreatePage.createProduct(buildSimpleProduct())
        ).name;

        secondProduct = (
            await productCreatePage.createProduct(buildSimpleProduct())
        ).name;

        credentials = await register(shopPage, buildCustomerCredentials());
    });

    test.afterEach(async () => {
        try {
            await productListPage.deleteProductsIfPresent([
                firstProduct,
                secondProduct,
            ]);
        } finally {
            await configurationPage.setBuyNowButton(buyNowWasEnabled);
        }
    });

    test("should hand the guest cart to a customer who signs in without one", async () => {
        await cartPage.addProductToCart(firstProduct);

        await authPage.login(credentials);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(firstProduct, 1);
    });

    test("should merge the guest cart into the cart the customer already had", async () => {
        await authPage.login(credentials);
        await cartPage.addProductToCart(firstProduct);
        await authPage.logout();

        await cartPage.addProductToCart(secondProduct);

        await authPage.login(credentials);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(firstProduct, 1);
        await cartPage.expectCartQuantity(secondProduct, 1);
    });

    test("should keep a basket merged out of a buy now across the login that follows", async () => {
        await cartPage.addProductToCart(firstProduct);
        await cartPage.buyNowProduct(secondProduct);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(firstProduct, 1);
        await cartPage.expectCartQuantity(secondProduct, 1);

        await authPage.login(credentials);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(firstProduct, 1);
        await cartPage.expectCartQuantity(secondProduct, 1);
    });

    test("should hand over both carts when the guest signs in from the buy now checkout", async () => {
        await cartPage.addProductToCart(firstProduct);
        await cartPage.buyNowProduct(secondProduct);

        await authPage.login(credentials);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(firstProduct, 1);
        await cartPage.expectCartQuantity(secondProduct, 1);
    });
});
