import { test, expect } from "../setup";

/**
 * Waiting time after cart operations to allow for UI updates.
 */
const CART_WAITING_TIME = 2000;

test("should increase the quantity from the mini cart drawer", async ({ page }) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();

    await page.getByRole("button", { name: "Shopping Cart" }).click();

    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);
});

test("should decrease the quantity from the mini cart drawer", async ({ page }) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();

    await page.getByRole("button", { name: "Shopping Cart" }).click();

    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.getByRole("button", { name: "Decrease Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.getByRole("button", { name: "Decrease Quantity" }).click();
    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);
});

test("should remove the product from the mini cart drawer", async ({ page }) => {
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

test("should remove all products from the cart view page", async ({ page }) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(1)
        .click();

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();

    await page.goto("checkout/cart");

    await page.locator(".icon-uncheck").first().waitFor({ state: "visible" });
    await page.locator(".icon-uncheck").first().click();
    await page
        .getByRole("button", { name: "Remove" })
        .first()
        .waitFor({ state: "visible" });

    await page.getByRole("button", { name: "Remove" }).first().click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page
            .getByText("Selected items successfully removed from cart.")
            .first(),
    ).toBeVisible();
});

test("should add product to cart", async ({ page }) => {
    await page.goto("");

    await page.getByPlaceholder("Search products here").fill("simple");
    await page.getByPlaceholder("Search products here").press("Enter");
    await page.getByRole("button", { name: "Add To Cart" }).first().click();

    await expect(
        page.getByText("Item Added Successfully").first(),
    ).toBeVisible();
});

test("should add different product to cart and update quantity from cart view page", async ({
    page,
}) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(1)
        .click();
    await page
        .getByText("Arctic Touchscreen Winter Gloves $21.00 Add To Cart")
        .first()
        .click();

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();

    await page.goto("checkout/cart");

    await page
        .getByLabel("Increase Quantity")
        .first()
        .waitFor({ state: "visible" });
    await page.getByLabel("Increase Quantity").first().click();
    await page.getByRole("button", { name: "Update Cart" }).click();

    await expect(
        page.getByText("Quantity updated successfully").first(),
    ).toBeVisible();
});

test("should add product to cart and decrement the quantity of product from the cart view page", async ({
    page,
}) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(1)
        .click();
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .nth(2)
        .click();

    await page.goto("checkout/cart");

    await page
        .getByLabel("Increase Quantity")
        .first()
        .waitFor({ state: "visible" });
    await page.getByLabel("Increase Quantity").first().click();

    await page
        .getByLabel("Decrease Quantity")
        .first()
        .waitFor({ state: "visible" });
    await page.getByLabel("Decrease Quantity").first().click();

    await page.getByRole("button", { name: "Update Cart" }).click();

    await expect(
        page.getByText("Quantity updated successfully").first(),
    ).toBeVisible();
});

test("should remove product from the cart view page", async ({ page }) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.goto("checkout/cart");

    await page
        .getByRole("button", { name: "Remove" })
        .first()
        .waitFor({ state: "visible" });

    await page.getByRole("button", { name: "Remove" }).first().click();
    await page.waitForTimeout(CART_WAITING_TIME);

    await page.getByRole("button", { name: "Agree", exact: true }).click();
    await expect(
        page.getByText("Item is successfully removed from the cart.").first(),
    ).toBeVisible();
    await page.waitForTimeout(CART_WAITING_TIME);
});
