import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../pages/admin/marketing/promotion/RuleDeletePage";
import { CartPage } from "../pages/shop/CartPage";
import type { BaseProduct } from "../pages/types/product.types";
import { uniqueStamp } from "../utils/faker";
import { formatPrice } from "../utils/prices";

const PRICE = 199;

function buildSimpleProduct(): BaseProduct {
    return {
        type: "simple",
        sku: `SKU-${uniqueStamp()}`,
        name: `Simple-${uniqueStamp()}`,
        shortDescription: "Short desc",
        description: "Full desc",
        price: PRICE,
        weight: 1,
        inventory: 100,
    };
}

test.describe("cart management", () => {
    let productListPage: ProductListPage;
    let cartPage: CartPage;
    let productName: string;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        productListPage = new ProductListPage(adminPage);
        cartPage = new CartPage(shopPage);

        const product = await new ProductCreatePage(adminPage).createProduct(
            buildSimpleProduct(),
        );

        productName = product.name;
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test.describe("mini cart drawer", () => {
        test("should add a product and show it with quantity one and a bin icon", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();

            await cartPage.expectMiniCartQuantity(productName, 1);
            await cartPage.expectMiniCartBinOffered(productName, true);
        });

        test("should increase and decrease the quantity and swap the bin icon for a minus", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.setMiniCartQuantity(productName, 3);

            await cartPage.expectMiniCartQuantity(productName, 3);
            await cartPage.expectMiniCartBinOffered(productName, false);

            await cartPage.setMiniCartQuantity(productName, 1);

            await cartPage.expectMiniCartQuantity(productName, 1);
            await cartPage.expectMiniCartBinOffered(productName, true);
        });

        test("should remove the item through the bin icon", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.removeFromMiniCartWithBin(productName);

            await cartPage.expectMiniCartItemAbsent(productName);
            await cartPage.openCart();
            await cartPage.expectCartEmpty();
        });

        test("should remove the item through the remove button", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.removeFromMiniCart(productName);

            await cartPage.expectMiniCartItemAbsent(productName);
            await cartPage.openCart();
            await cartPage.expectCartEmpty();
        });
    });

    test.describe("cart page", () => {
        test("should list the added product with its price as the subtotal", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();

            await cartPage.expectCartQuantity(productName, 1);
            await cartPage.expectCartBinOffered(productName, true);
            await cartPage.expectSummaryAmount("Subtotal", formatPrice(PRICE));
        });

        test("should update the quantity and recalculate the subtotal", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.setCartQuantity(productName, 2);

            await cartPage.expectCartBinOffered(productName, false);

            await cartPage.updateCart();

            await cartPage.expectCartQuantity(productName, 2);
            await cartPage.expectSummaryAmount("Subtotal", formatPrice(PRICE * 2));

            await cartPage.setCartQuantity(productName, 1);
            await cartPage.updateCart();

            await cartPage.expectCartQuantity(productName, 1);
            await cartPage.expectSummaryAmount("Subtotal", formatPrice(PRICE));
        });

        test("should remove the item through the bin icon", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeFromCartWithBin(productName);

            await cartPage.expectCartEmpty();
        });

        test("should remove the item through the remove link", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeFromCart(productName);

            await cartPage.expectCartEmpty();
        });

        test("should remove every selected item at once", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeAllFromCart();

            await cartPage.expectCartEmpty();
        });
    });

    test.describe("product page quantity", () => {
        test("should disable the minus button and hide the bin at quantity one", async () => {
            await cartPage.openProduct(productName);

            await cartPage.expectProductPageBinOffered(false);
            await cartPage.expectProductPageDecreaseDisabled(true);
        });

        test("should enable the minus button above quantity one", async () => {
            await cartPage.openProduct(productName);
            await cartPage.setProductPageQuantity(2);

            await cartPage.expectProductPageDecreaseDisabled(false);
            await cartPage.expectProductPageBinOffered(false);
        });
    });

    test.describe("coupons", () => {
        let ruleName: string;
        let couponCode: string;

        test.beforeEach(async ({ adminPage }) => {
            couponCode = `CART${uniqueStamp()}`;
            ruleName = await new RuleCreatePage(adminPage).createFixedCartRuleWithCoupon(
                couponCode,
                "10",
            );
        });

        test.afterEach(async ({ adminPage }) => {
            await new RuleDeletePage(adminPage).deleteCartRulesIfPresent([ruleName]);
        });

        test("should apply a valid coupon and discount the grand total", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.applyCoupon(couponCode);

            await cartPage.expectSummaryAmount("Discount Amount", formatPrice(10));
            await cartPage.expectSummaryAmount("Grand Total", formatPrice(PRICE - 10));
        });

        test("should refuse an unknown coupon and leave the total unchanged", async () => {
            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.attemptCoupon(`NOPE${uniqueStamp()}`);

            await cartPage.expectCouponRejected();
            await cartPage.expectSummaryAmount("Grand Total", formatPrice(PRICE));
        });
    });
});
