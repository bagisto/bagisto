import { test, expect } from "../../setup";
import { loginAsCustomer, addReview } from "../../utils/customer";
import { generateDescription, generateSKU } from "../../utils/faker";

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

test.describe("review management", () => {
    test.beforeEach(async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
        /**
         * Login as customer.
         */
        await loginAsCustomer(adminPage);

        /**
         * First adding review before updating status.
         */
        await addReview(adminPage);
    });

    test("should approve the review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now opening the side drawer for updating the status of review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right",
        );
        await iconRight[0].click();

        /**
         * Selecting the approve option.
         */
        await adminPage
            .locator('select[name="status"]')
            .selectOption("approved");

        /**
         * Saving the status.
         */
        await adminPage.click('button.primary-button:has-text("Save")');

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
        await expect(adminPage.locator("p.label-active")).toHaveText(
            "Approved",
        );
    });

    test("should disapprove the review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now opening the side drawer for updating the status of review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right",
        );
        await iconRight[0].click();

        /**
         * Selecting the disapprove option.
         */
        await adminPage
            .locator('select[name="status"]')
            .selectOption("disapproved");

        /**
         * Saving the status.
         */
        await adminPage.click('button.primary-button:has-text("Save")');

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Update Successfully",
            }),
        ).toBeVisible();
    });

    test("should approve the review via. mass update", async ({
        adminPage,
    }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        /**
         * Now hovering over the update status option and selecting the approve option.
         */
        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Pending"), a:has-text("Approved"), a:has-text("Disapproved")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Approved")');

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")',
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();
    });

    test("should disapprove the review via. mass update", async ({
        adminPage,
    }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        /**
         * Now hovering over the update status option and selecting the disapprove option.
         */
        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Pending"), a:has-text("Approved"), a:has-text("Disapproved")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Disapproved")');

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")',
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
        await expect(
            adminPage.locator("#app p", {
                hasText: "Selected Review Updated Successfully",
            }),
        ).toBeVisible();
    });

    test("should delete a review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now deleting the recent review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete",
        );
        await iconDelete[0].click();

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")',
        );

        /**
         * Clicking the agree button to delete the review.
         */
        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }
        await expect(
            adminPage.locator("#app p", {
                hasText: "Review Deleted Successfully",
            }),
        ).toBeVisible();
    });

    test("should mass delete a reviews", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        /**
         * Now selecting the delete option.
         */
        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")',
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the review is deleted successfully or not.
         */
        await expect(
            adminPage.getByText("Selected Review Deleted Successfully"),
        ).toBeVisible();
    });
});
