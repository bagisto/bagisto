import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import { generateName, generateDescription, generateSKU } from "../utils/faker";

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

test("should review a product", async ({ adminPage }) => {
    await createSimpleProduct(adminPage);
    await loginAsCustomer(adminPage);

    await adminPage.getByPlaceholder("Search products here").fill("simple");
    await adminPage.getByPlaceholder("Search products here").press("Enter");
    await adminPage.locator(".group img").first().click();
    await adminPage.getByRole("button", { name: "Reviews" }).click();
    await adminPage.waitForSelector("#review-tab");
    await adminPage.locator("#review-tab").getByText("Write a Review").click();
    await adminPage.locator("#review-tab span").nth(3).click();
    await adminPage.locator("#review-tab span").nth(4).click();
    await adminPage.getByPlaceholder("Title").click();
    await adminPage.getByPlaceholder("Title").fill(generateName());
    await adminPage.getByPlaceholder("Comment").click();
    await adminPage.getByPlaceholder("Comment").fill(generateDescription());
    await adminPage.getByRole("button", { name: "Submit Review" }).click();

    await expect(
        adminPage.getByText("Review submitted successfully.").first(),
    ).toBeVisible();
});
