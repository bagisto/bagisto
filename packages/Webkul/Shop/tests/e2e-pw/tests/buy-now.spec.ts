import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { ProductViewConfigurationPage } from "../pages/admin/configuration/ProductViewConfigurationPage";
import { CheckoutHelper } from "../pages/shop/checkout/CheckoutHelper";
import { CartPage } from "../pages/shop/CartPage";
import type { BaseProduct } from "../pages/types/product.types";
import { uniqueStamp } from "../utils/faker";

const PRICE = 149;

function buildSimpleProduct(): BaseProduct {
    return {
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `BuyNow-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: PRICE,
        weight: 1,
        inventory: 100,
    };
}

test.describe("buy now", () => {
    let productListPage: ProductListPage;
    let configurationPage: ProductViewConfigurationPage;
    let cartPage: CartPage;
    let checkoutHelper: CheckoutHelper;
    let basketProduct: string;
    let buyNowProduct: string;
    let buyNowWasEnabled: boolean;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        productListPage = new ProductListPage(adminPage);
        configurationPage = new ProductViewConfigurationPage(adminPage);
        cartPage = new CartPage(shopPage);
        checkoutHelper = new CheckoutHelper(shopPage);

        buyNowWasEnabled = await configurationPage.setBuyNowButton(true);

        const productCreatePage = new ProductCreatePage(adminPage);

        basketProduct = (
            await productCreatePage.createProduct(buildSimpleProduct())
        ).name;

        buyNowProduct = (
            await productCreatePage.createProduct(buildSimpleProduct())
        ).name;
    });

    test.afterEach(async () => {
        try {
            await productListPage.deleteProductsIfPresent([
                basketProduct,
                buyNowProduct,
            ]);
        } finally {
            await configurationPage.setBuyNowButton(buyNowWasEnabled);
        }
    });

    test("should keep the buy now item in the basket when its checkout is left", async () => {
        await cartPage.addProductToCart(basketProduct);
        await cartPage.buyNowProduct(buyNowProduct);

        await cartPage.openCart();

        await cartPage.expectCartQuantity(basketProduct, 1);
        await cartPage.expectCartQuantity(buyNowProduct, 1);
    });

    test("should leave the basket untouched once the bought product has been ordered", async () => {
        await cartPage.addProductToCart(basketProduct);
        await cartPage.buyNowProduct(buyNowProduct);

        await checkoutHelper.completeCheckoutHere();

        await cartPage.openCart();

        await cartPage.expectCartQuantity(basketProduct, 1);
        await cartPage.expectCartItemAbsent(buyNowProduct);
    });
});
