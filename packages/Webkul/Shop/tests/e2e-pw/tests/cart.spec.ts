import { test } from "../setup";
import { generatePhoneNumber } from "../utils/faker";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { RuleCreatePage } from "../pages/admin/marketing/promotion/RuleCreatePage";
import { CartPage } from "../pages/shop/CartPage";

test.describe("cart management", () => {
    let productName: string;

    test.beforeAll(async ({ adminPage }) => {
        productName = `Simple-${Date.now()}`;

        const productCreation = new ProductCreatePage(adminPage);

        await productCreation.createProduct({
            type: "simple",
            sku: `SKU-${Date.now()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    });

    test("should increase the quantity from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();

        await cartPage.expectQuantity(1);
        await cartPage.increaseQuantityTo(2);
        await cartPage.increaseQuantityTo(3);
    });

    test("should decrease the quantity from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();

        await cartPage.increaseQuantityTo(2);
        await cartPage.increaseQuantityTo(3);

        await cartPage.decreaseQuantityTo(2);
        await cartPage.decreaseQuantityTo(1);
    });

    test("should display bin icon in mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();

        await cartPage.expectQuantity(1);
        await cartPage.expectBinIconOffered();
    });

    test("should not display bin icon in mini cart drawer when quantity is greater than one", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();

        await cartPage.increaseQuantityTo(2);

        await cartPage.expectBinIconNotOffered();
    });

    test("should delete the cart item when clicking the bin icon", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();
        await cartPage.clickBinIcon();

        await cartPage.expectItemRemoved();
    });

    test("should display bin icon in cart view page when quantity is one", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();

        await cartPage.expectQuantity(1);
        await cartPage.expectBinIconOffered();
    });

    test("should not display bin icon in cart view page when quantity is greater than one", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.increaseQuantityFromCartView();

        await cartPage.expectQuantity(2);
        await cartPage.expectBinIconNotOffered();
    });

    test("should delete the cart item when clicking the bin icon in cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.clickBinIcon();

        await cartPage.expectItemRemoved();
    });

    test("should disable the minus icon and not render a bin icon on the product page at minimum quantity", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.openProductFromSearch(productName);

        await cartPage.expectBinIconNotOffered();
        await cartPage.expectDecreaseQuantityDisabled();
    });

    test("should enable the minus icon on the product page when quantity is greater than one", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.openProductFromSearch(productName);

        await cartPage.increaseQuantityFromCartView();

        await cartPage.expectQuantity(2);
        await cartPage.expectDecreaseQuantityEnabled();
        await cartPage.expectBinIconNotOffered();
    });

    test("should remove the product from the mini cart drawer", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.openMiniCart();
        await cartPage.removeProduct();

        await cartPage.expectItemRemoved();
    });

    test("should add product to cart", async ({ shopPage }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
    });

    test("should update quantity from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.increaseQuantityFromCartView();
        await cartPage.expectQuantity(2);
        await cartPage.updateCart();

        await cartPage.expectQuantityUpdated();
    });

    test("should decrement the quantity of a product from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.increaseQuantityFromCartView();
        await cartPage.expectQuantity(2);
        await cartPage.decreaseQuantityFromCartView();
        await cartPage.expectQuantity(1);
        await cartPage.updateCart();

        await cartPage.expectQuantityUpdated();
    });

    test("should remove product from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.removeProduct();

        await cartPage.expectItemRemoved();
    });

    test("should remove all products from the cart view page", async ({
        shopPage,
    }) => {
        const cartPage = new CartPage(shopPage);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();

        await cartPage.goToCartView();
        await cartPage.removeAllFromCartView();

        await cartPage.expectSelectedItemsRemoved();
    });

    test("should apply coupon", async ({ adminPage, shopPage }) => {
        const ruleCreatePage = new RuleCreatePage(adminPage);
        const cartPage = new CartPage(shopPage);
        const couponCode = generatePhoneNumber();

        await ruleCreatePage.createFixedCartRuleWithCoupon(couponCode);

        await cartPage.gotoHome();
        await cartPage.searchProduct(productName);
        await cartPage.addFirstProductToCart();
        await cartPage.expectItemAdded();
        await cartPage.goToCartView();
        await cartPage.applyCoupon(couponCode);

        await cartPage.expectCouponApplied();
    });
});
