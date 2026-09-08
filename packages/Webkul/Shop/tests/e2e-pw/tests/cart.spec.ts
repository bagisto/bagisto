import { test, withTinymce } from "../setup";
import { loginAsAdmin } from "../utils/admin";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { RuleCreatePage } from "../pages/admin/marketing/promotion/RuleCreatePage";
import { RuleDeletePage } from "../pages/admin/marketing/promotion/RuleDeletePage";
import { CartPage } from "../pages/shop/CartPage";
import { uniqueStamp } from "../utils/faker";
import { formatPrice } from "../utils/prices";

const PRICE = 199;

test.describe("cart management", () => {
    let productName: string;

    test.beforeAll(async ({ browser }) => {
        const context = await browser.newContext();
        const adminPage = withTinymce(await context.newPage());

        await loginAsAdmin(adminPage);

        productName = `Simple-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: PRICE,
            weight: 1,
            inventory: 100,
        });

        await context.close();
    });

    test.afterAll(async ({ browser }) => {
        const context = await browser.newContext();
        const page = await context.newPage();

        await loginAsAdmin(page);
        await new ProductListPage(page).deleteProductsIfPresent([productName]);
        await context.close();
    });

    test.describe("mini cart drawer", () => {
        test("should add a product and show it with quantity one and a bin icon", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();

            await cartPage.expectMiniCartQuantity(productName, 1);
            await cartPage.expectMiniCartBinOffered(productName, true);
        });

        test("should increase and decrease the quantity and swap the bin icon for a minus", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.setMiniCartQuantity(productName, 3);

            await cartPage.expectMiniCartQuantity(productName, 3);
            await cartPage.expectMiniCartBinOffered(productName, false);

            await cartPage.setMiniCartQuantity(productName, 1);

            await cartPage.expectMiniCartQuantity(productName, 1);
            await cartPage.expectMiniCartBinOffered(productName, true);
        });

        test("should remove the item through the bin icon", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.removeFromMiniCartWithBin(productName);

            await cartPage.expectMiniCartItemAbsent(productName);
            await cartPage.openCart();
            await cartPage.expectCartEmpty();
        });

        test("should remove the item through the remove button", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openMiniCart();
            await cartPage.removeFromMiniCart(productName);

            await cartPage.expectMiniCartItemAbsent(productName);
            await cartPage.openCart();
            await cartPage.expectCartEmpty();
        });
    });

    test.describe("cart page", () => {
        test("should list the added product with its price as the subtotal", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();

            await cartPage.expectCartQuantity(productName, 1);
            await cartPage.expectCartBinOffered(productName, true);
            await cartPage.expectSummaryAmount("Subtotal", formatPrice(PRICE));
        });

        test("should update the quantity and recalculate the subtotal", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

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

        test("should remove the item through the bin icon", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeFromCartWithBin(productName);

            await cartPage.expectCartEmpty();
        });

        test("should remove the item through the remove link", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeFromCart(productName);

            await cartPage.expectCartEmpty();
        });

        test("should remove every selected item at once", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.removeAllFromCart();

            await cartPage.expectCartEmpty();
        });
    });

    test.describe("product page quantity", () => {
        test("should disable the minus button and hide the bin at quantity one", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.openProduct(productName);

            await cartPage.expectProductPageBinOffered(false);
            await cartPage.expectProductPageDecreaseDisabled(true);
        });

        test("should enable the minus button above quantity one", async ({ shopPage }) => {
            const cartPage = new CartPage(shopPage);

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

        test("should apply a valid coupon and discount the grand total", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.applyCoupon(couponCode);

            await cartPage.expectSummaryAmount("Discount Amount", formatPrice(10));
            await cartPage.expectSummaryAmount("Grand Total", formatPrice(PRICE - 10));
        });

        test("should refuse an unknown coupon and leave the total unchanged", async ({
            shopPage,
        }) => {
            const cartPage = new CartPage(shopPage);

            await cartPage.addProductToCart(productName);
            await cartPage.openCart();
            await cartPage.attemptCoupon(`NOPE${uniqueStamp()}`);

            await cartPage.expectCouponRejected();
            await cartPage.expectSummaryAmount("Grand Total", formatPrice(PRICE));
        });
    });
});
