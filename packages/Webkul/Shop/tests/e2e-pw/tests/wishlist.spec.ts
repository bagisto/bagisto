import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import { ProductCreation } from "../pages/admin/catalog/products/ProductCreatePage";
import { WishlistPage } from "../pages/shop/WishlistPage";

test.beforeAll(
    "should create simple product to add in wishlist",
    async ({ adminPage }) => {
        const productCreation = new ProductCreation(adminPage);

        await productCreation.createProduct({
            type: "simple",
            sku: `SKU-${Date.now()}`,
            name: `Simple-${Date.now()}`,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });
    },
);

test("should add wishlist", async ({ shopPage }) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await wishlistPage.expectWishlistAdded();
});

test("should remove wishlist", async ({ shopPage }) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await wishlistPage.removeFirstWishlistItem();
    await wishlistPage.expectWishlistRemoved();
});

test("should display bin icon in wishlist when product quantity is one", async ({
    shopPage,
}) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await shopPage.goto("customer/account/wishlist");

    await expect(shopPage.locator(".icon-bin").nth(1)).toBeVisible();
});

test("should not display bin icon in wishlist when product quantity is greater than one", async ({
    shopPage,
}) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await shopPage.goto("customer/account/wishlist");
    await wishlistPage.increaseQuantityFromWishlishtView();

    await expect(shopPage.locator(".icon-bin").nth(1)).not.toBeVisible();
});

test("should remove using bin icon in wishlist", async ({ shopPage }) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await shopPage.goto("customer/account/wishlist");
    await wishlistPage.clickBinIcon();
    await expect(
        shopPage.getByText("Item Successfully Removed From Wishlist").first(),
    ).toBeVisible();
});

test("should clear all wishlist", async ({ shopPage }) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await wishlistPage.clearWishlist();
    await wishlistPage.expectWishlistRemoved();
});
