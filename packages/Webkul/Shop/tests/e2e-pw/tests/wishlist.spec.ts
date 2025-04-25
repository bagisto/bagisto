import { test, expect } from "../setup";
import { addWishlist, loginAsCustomer } from "../utils/customer";

test("should add wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);
});

test("should remove wishlist", async ({ page }) => {
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

test("should clear all wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page.goto("");
    await page.getByLabel("Profile").click();
    await page.getByRole("link", { name: "Wishlist", exact: true }).click();
    await page.getByText("Delete All", { exact: true }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Item Successfully Removed From Wishlist").first()
    ).toBeVisible();
});
