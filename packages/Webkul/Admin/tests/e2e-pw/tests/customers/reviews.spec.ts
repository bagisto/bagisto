import { test, expect } from "../../setup";
import { loginAsCustomer, addReview } from "../../utils/customer";
import { generateDescription, generateSKU } from "../../utils/faker";
import { CustomerReviewsPage } from "../../pages/admin/customers/CustomerReviewsPage";
import type { AdminPage } from "../../setup";

async function createSimpleProduct(adminPage: AdminPage) {
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
    await adminPage.locator('input[name="inventories\[1\]"]').click();
    await adminPage.locator('input[name="inventories\[1\]"]').fill("5000");
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

test.describe("review management", () => {
    test.beforeEach(async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
        await loginAsCustomer(adminPage);
        await addReview(adminPage);
    });

    test("should approve the review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.updateFirstReviewStatus("approved");
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
        await expect(adminPage.locator("p.label-active")).toHaveText(
            "Approved",
        );
    });

    test("should disapprove the review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.updateFirstReviewStatus("disapproved");
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Update Successfully",
            }),
        ).toBeVisible();
    });

    test("should approve the review via mass update", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.selectFirstReviewForMassActions();
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassUpdateStatus("Approved");
        await reviewsPage.confirmAgreeDialog();
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();
    });

    test("should disapprove the review via mass update", async ({
        adminPage,
    }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.selectFirstReviewForMassActions();
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassUpdateStatus("Disapproved");
        await reviewsPage.confirmAgreeDialog();
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();
    });

    test("should delete a review", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.open();
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete",
        );
        await iconDelete[0].click();
        await reviewsPage.confirmAgreeDialog();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Deleted Successfully",
            }),
        ).toBeVisible();
    });

    test("should mass delete reviews", async ({ adminPage }) => {
        const reviewsPage = new CustomerReviewsPage(adminPage);
        await reviewsPage.selectFirstReviewForMassActions();
        await reviewsPage.openSelectActionMenu();
        await reviewsPage.applyMassDelete();
        await reviewsPage.confirmAgreeDialog();
        await expect(
            adminPage.getByText("Selected Review Deleted Successfully"),
        ).toBeVisible();
    });
});
