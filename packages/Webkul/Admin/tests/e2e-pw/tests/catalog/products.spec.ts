import { test, expect } from "../../setup";
import {
    generateSKU,
    generateName,
    generateDescription,
    getImageFile,
} from "../../utils/faker";

async function createSimpleProduct(adminPage) {
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

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Save Product")'
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

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
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
     * Settings Section.
     */
    await adminPage
        .locator(".mt-3\\.5 > div:nth-child(2) > div:nth-child(3) > div")
        .first()
        .click();
    await adminPage.locator(".relative > label").first().click();
    await adminPage.locator("div:nth-child(3) > .relative > label").click();
    await adminPage.locator("div:nth-child(4) > .relative > label").click();
    await adminPage.locator("div:nth-child(5) > .relative > label").click();
    await adminPage.locator("div:nth-child(6) > .relative > label").click();

    /**
     * Inventories Section.
     */
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Categories Section.
     */
    await adminPage
        .locator("label")
        .filter({ hasText: "Men" })
        .locator("span")
        .click();

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    await expect(
        adminPage.getByText("Product updated successfully")
    ).toBeVisible();
}

async function createConfigurableProduct(adminPage) {
    /**
     * Main product data which we will use to create the Configurable product.
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
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("configurable");
    await adminPage
        .locator('select[name="attribute_family_id"]')
        .selectOption("1");
    await adminPage.locator('input[name="sku"]').fill(generateSKU());
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to Configurable Attributes modal, where
     * all the configuration on varients need to be done.
     */
    await adminPage.waitForSelector('p:has-text("Configurable Attributes")');

    /**
     * Removing Attributes.
     */
    await adminPage.getByRole('paragraph').filter({ hasText: 'Red' }).locator('span').click();
    await adminPage.getByRole('paragraph').filter({ hasText: 'Green' }).locator('span').click();
    await adminPage.getByRole('paragraph').filter({ hasText: 'Yellow' }).locator('span').click();
    await adminPage.locator('div:nth-child(2) > div > p > .icon-cross').first().click();
    await adminPage.locator('div:nth-child(2) > div > p > .icon-cross').first().click();
    await adminPage.getByRole('button', { name: 'Save Product' }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector('p:has-text("Product created successfully")');

    /**
     * General Section.
     */
    await adminPage.locator("#product_number").fill(product.productNumber);
    await adminPage.locator("#name").fill(product.name);

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
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
     * Settings Section.
     */
    await adminPage
        .locator(".mt-3\\.5 > div:nth-child(2) > div:nth-child(3) > div")
        .first()
        .click();
    await adminPage.locator(".relative > label").first().click();
    await adminPage.locator("div:nth-child(3) > .relative > label").click();
    await adminPage.locator("div:nth-child(4) > .relative > label").click();
    await adminPage.locator("div:nth-child(5) > .relative > label").click();
    await adminPage.locator("div:nth-child(6) > .relative > label").click();

    /**
     * Variations Section.
     */

    /**
     * Adding new varient.
     */
    await adminPage.getByText('Add Variant').click();
    await adminPage.locator('select[name="color"]').selectOption('1');
    await adminPage.locator('select[name="size"]').selectOption('6');
    await adminPage.getByRole('button', { name: 'Add' }).click();
    await adminPage.locator('input[name="name"]').nth(1).fill(generateName());
    await adminPage.locator('input[name="price"]').fill('100');
    await adminPage.locator('input[name="weight"]').fill('10');
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill('10');
    const skuValue = await adminPage.locator('input[name="sku"]').nth(1).inputValue();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
    await expect(adminPage.getByText(`${skuValue}`)).toBeVisible();

    /**
     * Adding price to all varients through multiselect.
     */
    await adminPage.locator('.icon-uncheckbox').first().click();
    await adminPage.getByRole('button', { name: 'Select Action ' }).click();
    await adminPage.getByText('Edit Prices').click();
    await adminPage.getByText('Are you sure?').click();
    await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();
    await adminPage.getByRole('paragraph').filter({ hasText: 'Edit Prices' }).click();
    await adminPage.locator('input[name="price"]').click();
    await adminPage.locator('input[name="price"]').fill('100');
    await adminPage.getByRole('button', { name: 'Apply to All' }).click();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
    await adminPage.getByText('$100.00').first().click();

    /**
     * Adding Inventories to all varients through multiselect.
     */
    await adminPage.locator('.icon-uncheckbox').first().click();
    await adminPage.getByRole('button', { name: 'Select Action ' }).click();
    await adminPage.getByText('Edit Inventories').click();
    await adminPage.getByText('Are you sure?').click();
    await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill('010');
    await adminPage.getByRole('button', { name: 'Apply to All' }).click();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
    await adminPage.getByText('10 Qty').first().click();

    /**
     * Adding Weight to all varients through multiselect.
     */
    await adminPage.locator('.icon-uncheckbox').first().click();
    await adminPage.getByRole('button', { name: 'Select Action ' }).click();
    await adminPage.getByText('Edit Weight').click();
    await adminPage.getByText('Are you sure?').click();
    await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();
    await adminPage.locator('input[name="weight"]').click();
    await adminPage.locator('input[name="weight"]').fill('01');
    await adminPage.getByRole('button', { name: 'Apply to All' }).click();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

    /**
     * Saving the configurable product.
     */
    await adminPage.getByRole('button', { name: 'Save Product' }).click();
}

async function createGroupedProduct(adminPage) {
    createSimpleProduct(adminPage);
    /**
     * Main product data which we will use to create the Grouped product.
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
    await adminPage.goto("admin/catalog/products");
    await adminPage.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("grouped");
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
        'button.primary-button:has-text("Save Product")'
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

    /**
     * Description Section.
     */
    await adminPage.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
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



}

test.describe("product management", () => {
    test("should create a simple product", async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
    });

    test("should edit a simple product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
        );
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        // Content will be added here. Currently just checking the general save button.

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        await expect(
            adminPage.getByText("Product updated successfully")
        ).toBeVisible();
    });

    test("should create a configurable product", async ({ adminPage }) => {
        await createConfigurableProduct(adminPage);
    });

    test("should create a grouped product", async ({ adminPage }) => {
        await createGroupedProduct(adminPage);
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 }
        );
        await adminPage.click('a:has-text("Active")');

        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Selected Products Updated Successfully")
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" }
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });

        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully")
        ).toBeVisible();
    });
});
