import { test, expect } from "../setup";
import { loginAsCustomer } from "../utils/customer";
import { generateName, generateDescription, generateSKU } from "../utils/faker";
import { ReviewPage } from "../pages/shop/ReviewPage";

async function createSimpleProduct(adminPage: any) {
    const product = {
        name: `simple-${Date.now()}`,
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
        weight: "25",
    };

    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")',
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();
    await adminPage.locator('select[name="type"]').selectOption("simple");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Save Product")',
    );
    await adminPage.waitForSelector('form[enctype="multipart/form-data"]');
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription,
    );
    await adminPage.fillInTinymce("#description_ifr", product.description);
    await adminPage.locator("#meta_title").fill(product.name);
    await adminPage.locator("#meta_keywords").fill(product.name);
    await adminPage.locator("#meta_description").fill(product.shortDescription);
    await adminPage.locator("#price").fill(product.price);
    await adminPage.locator("#weight").fill(product.weight);
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");
    await adminPage.getByRole("button", { name: "Save Product" }).click();
    await expect(adminPage.locator("#app")).toContainText(
        /product updated successfully/i,
    );
    await adminPage.goto("admin/catalog/products");

    await expect(
        adminPage
            .locator("p.break-all.text-base")
            .filter({ hasText: product.name }),
    ).toBeVisible();
}

test("should review a product", async ({ adminPage, shopPage }) => {
    const reviewPage = new ReviewPage(shopPage);

    await createSimpleProduct(adminPage);
    await loginAsCustomer(shopPage);

    await reviewPage.searchProduct("simple");
    await reviewPage.openFirstProduct();
    await reviewPage.writeReview(generateName(), generateDescription());
    await reviewPage.expectReviewSuccess();
});
