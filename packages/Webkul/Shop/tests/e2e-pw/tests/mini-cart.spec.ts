import { test, expect } from "../setup";

test("Increment", async ({ page }) => {
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();
    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await page.getByRole("button", { name: "Increase Quantity" }).click();

    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
});

test("Decrement", async ({ page }) => {
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();
    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await page.getByRole("button", { name: "Decrease Quantity" }).click();
    await page.getByRole("button", { name: "Decrease Quantity" }).click();

    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
});

test("Remove", async ({ page }) => {
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();
    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("button", { name: "Remove" }).click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Item is successfully removed from the cart.").first()
    ).toBeVisible();
});
