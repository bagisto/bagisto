import { test, expect } from "../../../setup";
import { generateDescription, generateSKU } from "../../../utils/faker";

async function createSimpleProduct(adminPage) {
    /**
     * Main product data which we will use to create the product.
     */
    const product = {
        name: `simple-${Date.now()}`,
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
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")',
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("simple");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Save Product")',
    );

    /**
     * Waiting for the main form to be visible.
     */
    await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);
    const name = await adminPage.locator('input[name="name"]').inputValue();

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription,
    );
    await adminPage.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await adminPage.locator("#meta_title").fill(product.name);
    await adminPage.locator("#meta_keywords").fill(product.name);
    await adminPage.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await adminPage.locator("#price").fill(product.price);

    /**
     * Shipping Section.
     */
    await adminPage.locator("#weight").fill(product.weight);

    /**
     * Inventories Section.
     */
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        /product updated successfully/i,
    );

    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage
            .locator("p.break-all.text-base")
            .filter({ hasText: product.name }),
    ).toBeVisible();
}

test.describe("should verfiy google captcha verfication", () => {
    /**
     * Enable captcha before each test
     */
    test.beforeEach(async ({ adminPage }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }

        await adminPage
            .getByRole("textbox", { name: "Project ID" })
            .fill("123456");
        await adminPage.getByRole("textbox", { name: "API Key" }).fill("test");
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test.afterEach(async ({ adminPage }) => {
        /**
         * Disable captcha after each test
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");
        await toggle.click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test("should display google captcha on customer sigin page", async ({
        adminPage,
    }) => {
        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign In" }).click();
        await expect(adminPage.locator("#recaptcha-token")).toBeAttached();
    });

    test("should display google captcha on customer sign up page", async ({
        adminPage,
    }) => {
        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign Up" }).click();
        await expect(adminPage.locator("#recaptcha-token")).toBeAttached();
    });

    test("should display google captcha on forgot passowrd page", async ({
        adminPage,
    }) => {
        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign In" }).click();
        await adminPage.getByRole("link", { name: "Forgot Password?" }).click();
        await expect(adminPage.locator("#recaptcha-token")).toBeAttached();
    });

    test("should display google captcha on contact us page", async ({
        adminPage,
    }) => {
        /**
         * Verify captcha
         */
        await adminPage.goto("contact-us");
        await expect(adminPage.locator("#recaptcha-token")).toBeAttached();
    });

    test("should display google captcha on product checkout sigin page", async ({
        adminPage,
    }) => {
        await createSimpleProduct(adminPage);
        await adminPage.goto("");
        await adminPage.getByPlaceholder("Search products here").fill("simple");
        await adminPage.getByPlaceholder("Search products here").press("Enter");

        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
        await adminPage
            .getByRole("paragraph")
            .filter({ hasText: "Item Added Successfully" })
            .click();
        await adminPage
            .locator(
                "(//span[contains(@class, 'icon-cart') and @role='button' and @tabindex='0'])[1]",
            )
            .click();
        await adminPage
            .locator('(//a[contains(., " Continue to Checkout ")])[1]')
            .click();
        await adminPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Verify captcha
         */
        await adminPage.waitForTimeout(3000);
        await expect(adminPage.locator("#recaptcha-token")).toBeAttached();
    });
});
