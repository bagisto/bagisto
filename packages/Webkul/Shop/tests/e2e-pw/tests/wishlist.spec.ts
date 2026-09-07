import { test } from "../setup";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { CartPage } from "../pages/shop/CartPage";
import { WishlistPage } from "../pages/shop/WishlistPage";
import { loginAsCustomer } from "../utils/customer";
import { uniqueStamp } from "../utils/faker";

test.describe("wishlist", () => {
    let productName: string;
    let productListPage: ProductListPage;
    let wishlistPage: WishlistPage;

    test.beforeEach(async ({ adminPage, shopPage }) => {
        productListPage = new ProductListPage(adminPage);
        wishlistPage = new WishlistPage(shopPage);
        productName = `Simple-${uniqueStamp()}`;

        await new ProductCreatePage(adminPage).createProduct({
            type: "simple",
            sku: `SKU-${uniqueStamp()}`,
            name: productName,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        });

        await loginAsCustomer(shopPage);
        await wishlistPage.addToWishlistFromListing(productName);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should list the product on the wishlist page with a bin icon at quantity one", async () => {
        await wishlistPage.open();

        await wishlistPage.expectItemListed(productName);
        await wishlistPage.expectBinOffered(productName, true);
    });

    test("should hide the bin icon once the quantity is above one", async () => {
        await wishlistPage.open();
        await wishlistPage.increaseQuantity(productName);

        await wishlistPage.expectBinOffered(productName, false);
    });

    test("should remove the product from the listing heart icon", async () => {
        await wishlistPage.removeFromListing(productName);

        await wishlistPage.open();
        await wishlistPage.expectItemAbsent(productName);
    });

    test("should remove the product through the bin icon", async () => {
        await wishlistPage.open();
        await wishlistPage.removeWithBin(productName);

        await wishlistPage.expectItemAbsent(productName);
    });

    test("should move the product to the cart", async ({ shopPage }) => {
        await wishlistPage.open();
        await wishlistPage.moveToCart(productName);

        await wishlistPage.expectItemAbsent(productName);

        const cartPage = new CartPage(shopPage);

        await cartPage.openCart();
        await cartPage.expectCartQuantity(productName, 1);
    });

    test("should clear the whole wishlist", async () => {
        await wishlistPage.open();
        await wishlistPage.deleteAll();

        await wishlistPage.expectEmpty();
    });
});
