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

test("should clear all wishlist", async ({ shopPage }) => {
    const wishlistPage = new WishlistPage(shopPage);

    await loginAsCustomer(shopPage);
    await wishlistPage.addProductToWishlist();
    await wishlistPage.clearWishlist();
    await wishlistPage.expectWishlistRemoved();
});
