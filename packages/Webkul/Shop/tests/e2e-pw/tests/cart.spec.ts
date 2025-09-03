import { test, expect } from "../setup";
import { generateName, generateDescription, generatePhoneNumber } from '../utils/faker';

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
    await page.getByRole("button", { name: "Increase Quantity" }).click();

    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
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
    await page.getByRole("button", { name: "Increase Quantity" }).click();
    await page.getByRole("button", { name: "Decrease Quantity" }).click();
    await page.getByRole("button", { name: "Decrease Quantity" }).click();

    await expect(
        page.locator("svg.text-blue.animate-spin.font-semibold")
    ).toBeVisible();
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

test("should add product to cart", async ({ page }) => {
    await page.goto("");

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .waitFor({ state: "visible" });

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();

    await expect(
        page.getByText("Item Added Successfully").first()
    ).toBeVisible();
});

test("should add different product to cart and update quantity from cart view page", async ({ page }) => {
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

test("should add product to cart and decrement the quantity of product from the cart view page", async ({ page }) => {
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

test("should remove product from the cart view page", async ({ page }) => {
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
        page.getByText("Selected items successfully removed from cart.").first()
    ).toBeVisible();
});

test("should apply coupon", async ({ page }) => {
    const couponCode = generatePhoneNumber();

    /**
     * Login to admin panel.
     */
    const adminCredentials = {
        email: "admin@example.com",
        password: "admin123",
    };
    await page.goto("admin/login");
    await page.getByPlaceholder("Email Address").click();
    await page.getByPlaceholder("Email Address").fill(adminCredentials.email);
    await page.getByPlaceholder("Password").click();
    await page.getByPlaceholder("Password").fill(adminCredentials.password);
    await page.getByRole("button", { name: "Sign In" }).click();

    /**
     * Reaching to the create cart rules page.
     */
    await page.goto("admin/marketing/promotions/cart-rules");
    await page.waitForSelector(
        'a.primary-button:has-text("Create Cart Rule")'
    );
    await page.click('a.primary-button:has-text("Create Cart Rule")');

    /**
     * Waiting for the main form to be visible.
     */
    await page.waitForSelector(
        'form[action*="/promotions/cart-rules/create"]'
    );

    /**
     * General Section.
     */
    await page.fill("#name", generateName());
    await page.fill("#description", generateDescription());
    await page.locator('#coupon_type').selectOption('1');
    await page.locator('#use_auto_generation').selectOption('0');
    await page.getByRole('textbox', { name: 'Coupon Code' }).fill(couponCode);
    await page.getByRole('textbox', { name: 'Uses Per Coupon' }).fill('100');
    await page.getByRole('textbox', { name: 'Uses Per Customer' }).fill('100');

    /**
     * Conditions Section.
     */
    await page.click('div.secondary-button:has-text("Add Condition")');

    /**
     * Selecting the condition attribute.
     */
    await page.waitForSelector('select[id="conditions\\[0\\]\\[attribute\\]"]');
    await page.locator('[id="conditions\\[0\\]\\[attribute\\]"]').selectOption('cart_item|quantity');
    await page.locator('select[name="conditions\\[0\\]\\[operator\\]"]').selectOption('>=');

    /**
     * Filling the condition value.
     */
    await page.locator('input[name="conditions\\[0\\]\\[value\\]"]').fill('1');

    /**
     * Actions Section.
     */
    await page.locator('#action_type').selectOption('by_fixed');
    await page.fill('input[name="discount_amount"]', "10");

    /**
     * Settings Section.
     */
    await page.fill('input[name="sort_order"]', "1");

    // Selecting the channel and verifying it.
    await page.click('label[for="channel__1"]');
    await expect(page.locator("input#channel__1")).toBeChecked();

    // Selecting the customer group and verifying it.
    await page.locator('#customer_group__1').nth(1).click();
    await page.click('label[for="customer_group__2"]');
    await expect(page.locator("input#customer_group__2")).toBeChecked();

    // Clicking the status and verify the toggle state.
    await page.click('label[for="status"]');
    const toggleInput = await page.locator('input[name="status"]');
    await expect(toggleInput).toBeChecked();

    /**
     * Saving the cart rule.
     */
    await page.click('button.primary-button:has-text("Save Cart Rule")');
    await expect(page.locator('#app')).toContainText('Cart rule created successfully');

    /**
     * Go to Shop
     */
    await page.goto('');
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
    await page.getByRole('button', { name: 'Apply Coupon' }).click();
    await page.getByPlaceholder('Enter your code').click();
    await page.getByPlaceholder('Enter your code').fill(couponCode);
    await page.getByRole('button', { name: 'Apply', exact: true }).click();
    await expect(page.getByRole('paragraph').filter({ hasText: 'Coupon code applied' })).toBeVisible();
});
