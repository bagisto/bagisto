import { test, expect } from "../../../setup";
import {
    generateDescription,
    generateRandomNumericString,
    getImageFile,
    generateSKU,
} from "../../../utils/faker";

test.describe("product configuration", () => {
    test.beforeEach(async ({ adminPage }) => {
        /**
         * Navigate to the configuration page.
         */
        await adminPage.goto("admin/configuration/catalog/products");
    });

    test("should update the compare and image search", async ({
        adminPage,
    }) => {
        await adminPage.click(
            'label[for="catalog[products][settings][compare_option]"]'
        );
        await adminPage.click(
            'label[for="catalog[products][settings][image_search]"]'
        );

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    /**
     * Verify group price while creating product
     */
    test("should update the product group price after delete", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
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
        await expect(
            adminPage.locator("text =Product created successfully").first()
        ).toBeVisible();

        /**
         * create group price
         */
        await adminPage.getByText("Add New").click();

        await adminPage
            .locator('select[name="customer_group_id"]')
            .selectOption("1");
        await adminPage.locator('input[name="qty"]').fill("022");
        await adminPage.locator('input[name="value"]').fill("045");
        await adminPage
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            adminPage.getByText("For 022 Qty at fixed price of")
        ).toBeVisible();
        await adminPage.waitForTimeout(1000);

        await adminPage.getByText("Add New").click();
        await adminPage
            .locator('select[name="customer_group_id"]')
            .selectOption("2");
        await adminPage.locator('input[name="qty"]').fill("020");
        await adminPage
            .locator('select[name="value_type"]')
            .selectOption("discount");
        await adminPage.locator('input[name="value"]').fill("034");
        await adminPage
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            adminPage.getByText("For 020 Qty at discount of")
        ).toBeVisible();
        await adminPage.waitForTimeout(1000);

        await adminPage.getByText("Add New").click();
        await adminPage
            .locator('select[name="customer_group_id"]')
            .selectOption("3");
        await adminPage.locator('input[name="qty"]').fill("015");
        await adminPage.locator('input[name="value"]').fill("043");
        await adminPage
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            adminPage.getByText("For 015 Qty at fixed price of")
        ).toBeVisible();

        /**
         * Delete group price from middle
         */
        await adminPage.getByText("Edit").nth(2).click();
        await adminPage.getByRole("button", { name: "Delete" }).click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        /**
         * Verify the deletion
         */
        await expect(
            adminPage.getByText("For 022 Qty at fixed price of")
        ).toBeVisible();
        await expect(
            adminPage.getByText("For 020 Qty at discount of")
        ).not.toBeVisible();
        await expect(
            adminPage.getByText("For 015 Qty at fixed price of")
        ).toBeVisible();
    });

    /**
     * Update the product view page configuration.
     */
    test("should update the product view page configuration", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][product_view_page][no_of_related_products]"]'
            )
            .fill(generateRandomNumericString(2));
        await adminPage
            .locator(
                'input[name="catalog[products][product_view_page][no_of_up_sells_products]"]'
            )
            .fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the cart view page configuration", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][cart_view_page][no_of_cross_sells_products]"]'
            )
            .fill(generateRandomNumericString(2));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the store front configuration", async ({
        adminPage,
    }) => {
        await adminPage.selectOption(
            'select[name="catalog[products][storefront][mode]"]',
            "grid"
        );
        const defaultListMode = adminPage.locator(
            'select[name="catalog[products][storefront][mode]"]'
        );
        await expect(defaultListMode).toHaveValue("grid");

        await adminPage
            .locator(
                'input[name="catalog[products][storefront][products_per_page]"]'
            )
            .fill(generateRandomNumericString(2));

        await adminPage.selectOption(
            'select[name="catalog[products][storefront][sort_by]"]',
            "name-asc"
        );
        const sortBy = adminPage.locator(
            'select[name="catalog[products][storefront][sort_by]"]'
        );
        await expect(sortBy).toHaveValue("name-asc");

        await adminPage.click(
            'label[for="catalog[products][storefront][buy_now_button_display]"]'
        );

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the small image size and placeholder", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][cache_small_image][width]"]'
            )
            .fill(generateRandomNumericString(3));
        await adminPage
            .locator(
                'input[name="catalog[products][cache_small_image][height]"]'
            )
            .fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent("filechooser"),
            adminPage.click('label:has-text("Small Image Placeholder")'),
        ]);

        await fileChooser.setFiles(getImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage
            .locator(
                '[id="catalog\\[products\\]\\[cache_small_image\\]\\[url\\]\\[delete\\]"]'
            )
            .nth(1)
            .click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the medium image size and placeholder", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][cache_medium_image][width]"]'
            )
            .fill(generateRandomNumericString(3));
        await adminPage
            .locator(
                'input[name="catalog[products][cache_medium_image][height]"]'
            )
            .fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent("filechooser"),
            adminPage.click('label:has-text("Medium Image Placeholder")'),
        ]);

        await fileChooser.setFiles(getImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage
            .locator(
                '[id="catalog\\[products\\]\\[cache_medium_image\\]\\[url\\]\\[delete\\]"]'
            )
            .nth(1)
            .click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the large image size and placeholder", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][cache_large_image][width]"]'
            )
            .fill(generateRandomNumericString(3));
        await adminPage
            .locator(
                'input[name="catalog[products][cache_large_image][height]"]'
            )
            .fill(generateRandomNumericString(3));

        const [fileChooser] = await Promise.all([
            adminPage.waitForEvent("filechooser"),
            adminPage.click('label:has-text("Large Image Placeholder")'),
        ]);

        await fileChooser.setFiles(getImageFile());
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Delete the uploaded favicon.
         */
        await adminPage
            .locator(
                '[id="catalog\\[products\\]\\[cache_large_image\\]\\[url\\]\\[delete\\]"]'
            )
            .nth(1)
            .click();
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the review configuration", async ({ adminPage }) => {
        await adminPage.click(
            'label[for="catalog[products][review][guest_review]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][review][customer_review]"]'
        );

        await adminPage.selectOption(
            'select[name="catalog[products][review][summary]"]',
            "star_counts"
        );
        const searchEngine = adminPage.locator(
            'select[name="catalog[products][review][summary]"]'
        );
        await expect(searchEngine).toHaveValue("star_counts");

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update the allowed image and file upload size", async ({
        adminPage,
    }) => {
        await adminPage
            .locator(
                'input[name="catalog[products][attribute][image_attribute_upload_size]"]'
            )
            .fill(generateRandomNumericString(3));
        await adminPage
            .locator(
                'input[name="catalog[products][attribute][file_attribute_upload_size]"]'
            )
            .fill(generateRandomNumericString(3));
        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });

    test("should update social share configuration", async ({ adminPage }) => {
        await adminPage.click(
            'label[for="catalog[products][social_share][enabled]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][facebook]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][twitter]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][pinterest]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][whatsapp]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][linkedin]"]'
        );

        await adminPage.click(
            'label[for="catalog[products][social_share][email]"]'
        );

        await adminPage
            .locator(
                'input[name="catalog[products][social_share][share_message]"]'
            )
            .fill(generateDescription());

        await adminPage.click('button[type="submit"].primary-button:visible');

        /**
         * Verify the change is saved.
         */
        await expect(
            adminPage.locator("#app p", {
                hasText: "Configuration saved successfully",
            })
        ).toBeVisible();
    });
});
