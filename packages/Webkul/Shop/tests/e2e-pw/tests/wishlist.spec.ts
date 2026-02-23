import { test, expect } from "../setup";
import { addWishlist, loginAsCustomer } from "../utils/customer";
import { ProductCreation } from "../pages/product";

test.beforeAll("should create simple product to add in wishlist", async ({
    adminPage,
}) => {
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
});

test("should add wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);
});

test("should remove wishlist", async ({ page }) => {
    await loginAsCustomer(page);

    await addWishlist(page);

    await page.locator(".action-items > span").first().click();

    await expect(
        page.getByText("Item Successfully Removed From Wishlist").first(),
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
        page.getByText("Item Successfully Removed From Wishlist").first(),
    ).toBeVisible();
});
