import { test, expect } from "../setup";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { generateName, generateSKU, generateDescription } from "../utils/faker";

async function createSimpleProduct(page) {
    /**
     * Main product data which we will use to create the product.
     */
    const product = {
        name: generateName(),
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "25",
    };

    /**
     * Reaching to the create product page.
     */
    await page.goto("admin/catalog/products");
    await page.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await page.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await page.locator('select[name="type"]').selectOption("simple");
    await page.locator('select[name="attribute_family_id"]').selectOption("1");
    await page.locator('input[name="sku"]').fill(generateSKU());
    await page.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await page.waitForSelector(
        'button.primary-button:has-text("Save Product")'
    );

    /**
     * Waiting for the main form to be visible.
     */
    await page.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await page.locator("#product_number").fill(product.productNumber);
    await page.locator("#name").fill(product.name);

    /**
     * Description Section.
     */
    await page.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
    );
    await page.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await page.locator("#meta_title").fill(product.name);
    await page.locator("#meta_keywords").fill(product.name);
    await page.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await page.locator("#price").fill(product.price);

    /**
     * Shipping Section.
     */
    await page.locator("#weight").fill(product.weight);

    /**
     * Inventories Section.
     */
    await page.locator('input[name="inventories\\[1\\]"]').click();
    await page.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Categories Section.
     */
    await page
        .locator("label")
        .filter({ hasText: "Men" })
        .locator("span")
        .click();

    /**
     * Saving the product.
     */
    await page.getByRole("button", { name: "Save Product" }).click();

    return product;
}

test.describe("checkout", () => {
    test("guest should be able to checkout", async ({ shopPage }) => {
        /**
         * Login to admin panel.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };

        await shopPage.goto("admin/login");
        await shopPage.getByPlaceholder("Email Address").click();
        await shopPage
            .getByPlaceholder("Email Address")
            .fill(adminCredentials.email);
        await shopPage.getByPlaceholder("Password").click();
        await shopPage
            .getByPlaceholder("Password")
            .fill(adminCredentials.password);
        await shopPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Create simple product.
         */
        const product = await createSimpleProduct(shopPage);

        /**
         * Go to shop to buy a product.
         */
        await shopPage.goto("");
        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .getByLabel(product.name)
            .waitFor({ state: "visible" });

        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .getByLabel(product.name)
            .click();
        await shopPage.getByRole("button", { name: "Add To Cart" }).click();
        await expect(shopPage.locator("#app")).toContainText(
            "Item Added Successfully"
        );
        await shopPage.locator(".icon-cancel").first().click();
        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();

        /**
         * Fill Customer details.
         */
        await shopPage
            .getByPlaceholder("Company Name")
            .waitFor({ state: "visible" });
        await shopPage.getByPlaceholder("Company Name").click();
        await shopPage.getByPlaceholder("Company Name").fill("WEBKUL");
        await shopPage.getByPlaceholder("First Name").click();
        await shopPage.getByPlaceholder("First Name").fill("Demo");
        await shopPage.getByPlaceholder("First Name").press("Tab");
        await shopPage.getByPlaceholder("Last Name").fill("Demo");
        await shopPage.getByPlaceholder("Last Name").press("Tab");
        await shopPage
            .getByRole("textbox", { name: "email@example.com" })
            .press("CapsLock");
        await shopPage
            .getByRole("textbox", { name: "email@example.com" })
            .fill("Demo_ashdghsd@hjdg.sad");
        await shopPage
            .getByRole("textbox", { name: "email@example.com" })
            .press("Tab");
        await shopPage.getByPlaceholder("Street Address").fill("Demo2367");
        await shopPage.getByPlaceholder("Street Address").press("Tab");
        await shopPage
            .locator('select[name="billing\\.country"]')
            .selectOption("AI");
        await shopPage.getByPlaceholder("State").click();
        await shopPage.getByPlaceholder("State").fill("Demo");
        await shopPage.getByPlaceholder("City").click();
        await shopPage.getByPlaceholder("City").fill("Demo");
        await shopPage.getByPlaceholder("Zip/Postcode").click();
        await shopPage.getByPlaceholder("Zip/Postcode").fill("2673854");
        await shopPage.getByPlaceholder("Telephone").click();
        await shopPage.getByPlaceholder("Telephone").fill("9023723564");
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose shipping method.
         */
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose payment option.
         */
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Place order.
         */
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Thank you for your order!");
        await expect(
            shopPage.locator("text=Thank you for your order!")
        ).toBeVisible();

        /**
         * Check order to admin side.
         */
        await shopPage.goto("admin/sales/orders");
        await shopPage.locator(".row > div:nth-child(4) > a").first().click();
        await expect(
            shopPage.locator(".box-shadow > div:nth-child(2) > div").first()
        ).toBeVisible();
    });

    test("customer should be able to checkout", async ({ shopPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(shopPage);

        /**
         * Fill customer default address.
         */
        await addAddress(shopPage);

        /**
         * Go to the shop to buy a product.
         */
        await shopPage.goto("");
        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .waitFor({ state: "visible" });

        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .click();
        await expect(shopPage.locator("#app")).toContainText(
            "Item Added Successfully"
        );
        await shopPage.locator(".icon-cancel").first().click();
        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();
        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
            )
            .nth(0)
            .click();
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose shipping method.
         */
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose payment option.
         */
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Place order.
         */
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Thank you for your order!");
        await expect(
            shopPage.locator("text=Thank you for your order!")
        ).toBeVisible();

        /**
         * Login to admin panel.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await shopPage.goto("admin/login");
        await shopPage.getByPlaceholder("Email Address").click();
        await shopPage
            .getByPlaceholder("Email Address")
            .fill(adminCredentials.email);
        await shopPage.getByPlaceholder("Password").click();
        await shopPage
            .getByPlaceholder("Password")
            .fill(adminCredentials.password);
        await shopPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Check order to admin side.
         */
        await shopPage.goto("admin/sales/orders");
        await shopPage.locator(".row > div:nth-child(4) > a").first().click();
        await expect(
            shopPage.locator(".box-shadow > div:nth-child(2) > div").first()
        ).toBeVisible();
    });

    test("if use same address for shipping", async ({ shopPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(shopPage);

        /**
         * Fill customer default address.
         */
        await addAddress(shopPage);

        /**
         * Go to the shop to buy a product.
         */
        await shopPage.goto("");
        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .waitFor({ state: "visible" });

        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .click();
        await expect(shopPage.locator("#app")).toContainText(
            "Item Added Successfully"
        );
        await shopPage.locator(".icon-cancel").first().click();
        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();
        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
            )
            .nth(0)
            .click();

        /**
         * Enabled using same adress for shipping checkbox.
         */
        const isEnabled = shopPage.locator("#use_for_shipping").nth(1).check();

        /**
         * If not enabled, then we enable it.
         */
        if (!isEnabled) {
            const gdprsettingToggle = shopPage
                .locator("#use_for_shipping")
                .nth(1);
            await gdprsettingToggle.waitFor({
                state: "visible",
                timeout: 5000,
            });
            await shopPage.locator("#use_for_shipping").nth(1).click();
        }

        /**
         * Verifying enable state.
         */
        const toggleInput = shopPage.locator("#use_for_shipping").nth(1);
        await expect(toggleInput).toBeChecked();

        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose shipping method.
         */
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose payment option.
         */
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Place order.
         */
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Thank you for your order!");
        await expect(
            shopPage.locator("text=Thank you for your order!")
        ).toBeVisible();

        /**
         * Login to admin panel.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await shopPage.goto("admin/login");
        await shopPage.getByPlaceholder("Email Address").click();
        await shopPage
            .getByPlaceholder("Email Address")
            .fill(adminCredentials.email);
        await shopPage.getByPlaceholder("Password").click();
        await shopPage
            .getByPlaceholder("Password")
            .fill(adminCredentials.password);
        await shopPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Check order to admin side.
         */
        await shopPage.goto("admin/sales/orders");
        await shopPage.locator(".row > div:nth-child(4) > a").first().click();
        await expect(
            shopPage.locator(".box-shadow > div:nth-child(2) > div").first()
        ).toBeVisible();
    });

    test("if not use same address for shipping", async ({ shopPage }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(shopPage);

        /**
         * Fill customer default address.
         */
        await addAddress(shopPage);

        /**
         * Go to the shop to buy a product.
         */
        await shopPage.goto("");
        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .waitFor({ state: "visible" });
        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .nth(1)
            .waitFor({ state: "visible" });

        await shopPage
            .locator("#main div")
            .filter({ hasText: "New Products View All New" })
            .locator("button")
            .first()
            .click();
        await expect(shopPage.locator("#app")).toContainText(
            "Item Added Successfully"
        );
        await shopPage.locator(".icon-cancel").first().click();
        await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
        await shopPage
            .getByRole("link", { name: "Continue to Checkout" })
            .click();
        await shopPage
            .locator(
                'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
            )
            .nth(0)
            .click();

        /**
         * Disabled if not using same adress for shipping checkbox.
         */
        const isDisabled = shopPage
            .locator("#use_for_shipping")
            .nth(1)
            .uncheck();

        /**
         * If not disabled, then we disable it.
         */
        if (!isDisabled) {
            const gdprsettingToggle = shopPage
                .locator("#use_for_shipping")
                .nth(1);
            await gdprsettingToggle.waitFor({
                state: "visible",
                timeout: 5000,
            });
            await shopPage.locator("#use_for_shipping").nth(1).click();
        }

        /**
         * Verifying disable state.
         */
        const toggleInput = shopPage
            .locator("#use_for_shipping")
            .nth(1)
            .first();
        await expect(toggleInput).not.toBeChecked();

        /**
         * Add shipping address.
         */
        await shopPage
            .locator("div")
            .filter({ hasText: /^Add new address$/ })
            .nth(2)
            .click();

        await shopPage.getByRole("textbox", { name: "Company Name" }).click();
        await shopPage
            .getByRole("textbox", { name: "Company Name" })
            .fill("Webkul");
        await shopPage.getByRole("textbox", { name: "First Name" }).click();
        await shopPage.getByRole("textbox", { name: "First Name" }).fill("Sam");
        await shopPage.getByRole("textbox", { name: "Last Name" }).click();
        await shopPage
            .getByRole("textbox", { name: "Last Name" })
            .fill("LUren");
        await shopPage
            .getByRole("textbox", { name: "email@example.com" })
            .click();
        await shopPage
            .getByRole("textbox", { name: "email@example.com" })
            .fill("sam@example.com");
        await shopPage.getByRole("textbox", { name: "Street Address" }).click();
        await shopPage
            .getByRole("textbox", { name: "Street Address" })
            .fill("ARV");
        await shopPage
            .locator('select[name="shipping\\.country"]')
            .selectOption("AD");
        await shopPage.getByRole("textbox", { name: "State" }).click();
        await shopPage.getByRole("textbox", { name: "State" }).fill("any");
        await shopPage.getByRole("textbox", { name: "City" }).click();
        await shopPage.getByRole("textbox", { name: "City" }).fill("any");
        await shopPage.getByRole("textbox", { name: "Zip/Postcode" }).click();
        await shopPage
            .getByRole("textbox", { name: "Zip/Postcode" })
            .fill("123456");
        await shopPage.getByRole("textbox", { name: "Telephone" }).click();
        await shopPage
            .getByRole("textbox", { name: "Telephone" })
            .fill("123456");
        await shopPage.getByRole("button", { name: "Save" }).click();
        await shopPage.getByRole("button", { name: "Proceed" }).click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose shipping method.
         */
        await shopPage.waitForSelector("text=Free Shipping");
        await shopPage.getByText("Free Shipping").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Choose payment option.
         */
        await shopPage.waitForSelector("text=Cash On Delivery");
        await shopPage.getByText("Cash On Delivery").first().click();
        await shopPage.waitForTimeout(2000);

        /**
         * Place order.
         */
        await shopPage.getByRole("button", { name: "Place Order" }).click();
        await shopPage.waitForTimeout(2000);
        await shopPage.waitForSelector("text=Thank you for your order!");
        await expect(
            shopPage.locator("text=Thank you for your order!")
        ).toBeVisible();

        /**
         * Login to admin panel.
         */
        const adminCredentials = {
            email: "admin@example.com",
            password: "admin123",
        };
        await shopPage.goto("admin/login");
        await shopPage.getByPlaceholder("Email Address").click();
        await shopPage
            .getByPlaceholder("Email Address")
            .fill(adminCredentials.email);
        await shopPage.getByPlaceholder("Password").click();
        await shopPage
            .getByPlaceholder("Password")
            .fill(adminCredentials.password);
        await shopPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Check order to admin side.
         */
        await shopPage.goto("admin/sales/orders");
        await shopPage.locator(".row > div:nth-child(4) > a").first().click();
        await expect(
            shopPage.locator(".box-shadow > div:nth-child(2) > div").first()
        ).toBeVisible();
    });
});
