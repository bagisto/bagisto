import { test, expect } from "../setup";
import { addWishlist, loginAsCustomer } from "../utils/customer";

test("should add wishlist to the cart", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);
});

test("should remove wishlist from the cart", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page.locator(".action-items > span").first().click();
    await page
        .locator(
            "div:nth-child(9) > div:nth-child(2) > div > .-mt-9 > .action-items > span"
        )
        .first()
        .click();

    await expect(
        page.getByText("Item Successfully Removed From Wishlist").first()
    ).toBeVisible();
});
