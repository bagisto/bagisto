import { test, expect } from "../setup";

test("Increment", async ({ page }) => {
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
        page.getByText("Quantity updated successfully").first()
    ).toBeVisible();
});

test("Decrement", async ({ page }) => {
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
        page.getByText("Quantity updated successfully").first()
    ).toBeVisible();
});

test("Remove One", async ({ page }) => {
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();

    await page.goto("checkout/cart");
    await page
        .getByRole("button", { name: "Remove" })
        .first()
        .waitFor({ state: "visible" });
    await page.getByRole("button", { name: "Remove" }).first().click();
    await page.getByRole("button", { name: "Agree", exact: true }).click();

    await expect(
        page.getByText("Item is successfully removed from the cart.").first()
    ).toBeVisible();
});

test("Remove All", async ({ page }) => {
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
        page.getByText("Selected items successfully removed from cart.").first()
    ).toBeVisible();
});

// test('Apply Coupon', async ({ page }) => {
//     await page.goto('');
//     await page.locator('#main div').filter({ hasText: 'New Products View All New' }).locator('button').first().click();

//     await page.goto('checkout/cart);
//     await page.getByRole('button', { name: 'Apply Coupon' }).click();
//     await page.getByPlaceholder('Enter your code').click();
//     await page.getByPlaceholder('Enter your code').fill('12345');
//     await page.getByRole('button', { name: 'Apply', exact: true }).click();

//     await expect(page.getByText('Coupon code applied successfully.').first()).toBeVisible();
// });
