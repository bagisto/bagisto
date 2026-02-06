import { test, expect } from "../../setup";
import { fileURLToPath } from "url";
import path from "path";
import {
    generateSKU,
    generateName,
    generateDescription,
    generateLocation,
    generateRandomDateTime,
    generateHostname,
} from "../../utils/faker";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

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
     * Categories Section.
     */
    await adminPage
        .locator("label", { hasText: /^Men$/ })
        .locator("span.icon-uncheckbox")
        .first()
        .click();

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
        'button.primary-button:has-text("Create Product")',
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
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Red" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Green" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Yellow" })
        .locator("span")
        .click();
    await adminPage
        .locator("div:nth-child(2) > div > p > .icon-cross")
        .first()
        .click();
    await adminPage
        .locator("div:nth-child(2) > div > p > .icon-cross")
        .first()
        .click();
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await adminPage.waitForSelector(
        'p:has-text("Product created successfully")',
    );

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
     * Adding new variant.
     */
    await adminPage.getByText("Add Variant").click();
    await adminPage.locator('select[name="color"]').selectOption("1");
    await adminPage.locator('select[name="size"]').selectOption("6");
    await adminPage.getByRole("button", { name: "Add" }).click();
    await adminPage.locator('input[name="name"]').nth(1).fill(generateName());
    await adminPage.locator('input[name="price"]').fill("100");
    await adminPage.locator('input[name="weight"]').fill("10");
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("10");
    const skuValue = await adminPage
        .locator('input[name="sku"]')
        .nth(1)
        .inputValue();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await expect(adminPage.getByText(`${skuValue}`)).toBeVisible();

    /**
     * Adding price to all varients through multiselect.
     */
    await adminPage.locator(".icon-uncheckbox").first().click();
    await adminPage.getByRole("button", { name: "Select Action " }).click();
    await adminPage.getByText("Edit Prices").click();
    await adminPage.getByText("Are you sure?").click();
    await adminPage.getByRole("button", { name: "Agree", exact: true }).click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Edit Prices" })
        .click();
    await adminPage.locator('input[name="price"]').click();
    await adminPage.locator('input[name="price"]').fill("100");
    await adminPage.getByRole("button", { name: "Apply to All" }).click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await adminPage.getByText("$100.00").first().click();

    /**
     * Adding Inventories to all varients through multiselect.
     */
    await adminPage.locator(".icon-uncheckbox").first().click();
    await adminPage.getByRole("button", { name: "Select Action " }).click();
    await adminPage.getByText("Edit Inventories").click();
    await adminPage.getByText("Are you sure?").click();
    await adminPage.getByRole("button", { name: "Agree", exact: true }).click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("010");
    await adminPage.getByRole("button", { name: "Apply to All" }).click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await adminPage.getByText("10 Qty").first().click();

    /**
     * Adding Weight to all varients through multiselect.
     */
    await adminPage.locator(".icon-uncheckbox").first().click();
    await adminPage.getByRole("button", { name: "Select Action " }).click();
    await adminPage.getByText("Edit Weight").click();
    await adminPage.getByText("Are you sure?").click();
    await adminPage.getByRole("button", { name: "Agree", exact: true }).click();
    await adminPage.locator('input[name="weight"]').click();
    await adminPage.locator('input[name="weight"]').fill("01");
    await adminPage.getByRole("button", { name: "Apply to All" }).click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();

    /**
     * Saving the configurable product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        "Product updated successfully",
    );

    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage.getByRole("paragraph").filter({ hasText: product.name }),
    ).toBeVisible();
}

async function createGroupedProduct(adminPage) {
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
        'button.primary-button:has-text("Create Product")',
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
     * Adding products to make a group of products.
     */
    await adminPage.locator(".secondary-button").first().click();
    await adminPage.waitForSelector('p:has-text("Select Products")');
    await adminPage.getByRole("textbox", { name: "Search by name" }).click();
    await adminPage
        .getByRole("textbox", { name: "Search by name" })
        .fill("arc");
    await adminPage
        .locator("div")
        .filter({
            hasText:
                /^Arctic Touchscreen Winter GlovesSKU - SP-003\$21\.00100 Available$/,
        })
        .locator("label")
        .click();
    await adminPage
        .locator("div")
        .filter({
            hasText:
                /^Arctic Warmth Wool Blend SocksSKU - SP-004\$21\.00100 Available$/,
        })
        .locator("label")
        .click();
    await adminPage
        .locator("div")
        .filter({
            hasText:
                /^Arctic Bliss Stylish Winter ScarfSKU - SP-002\$17\.00100 Available$/,
        })
        .locator("label")
        .click();

    /**
     * Saving the added product.
     */
    await adminPage.getByText("Add Selected Product").click();

    /**
     * Waiting for the products to be added.
     */
    await adminPage.waitForSelector(
        'p:has-text("Arctic Touchscreen Winter Gloves")',
    );
    await adminPage.waitForSelector(
        'p:has-text("Arctic Warmth Wool Blend Socks")',
    );
    await adminPage.waitForSelector(
        'p:has-text("Arctic Bliss Stylish Winter")',
    );

    /**
     * Saving the configurable product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        "Product updated successfully",
    );

    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage.getByRole("paragraph").filter({ hasText: product.name }),
    ).toBeVisible();
}

async function createVirtualProduct(adminPage) {
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
        'button.primary-button:has-text("Create Product")',
    );
    await adminPage.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await adminPage.locator('select[name="type"]').selectOption("virtual");
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
     * Inventories Section.
     */
    await adminPage.locator('input[name="inventories\\[1\\]"]').click();
    await adminPage.locator('input[name="inventories\\[1\\]"]').fill("5000");

    /**
     * Categories Section.
     */
    await adminPage
        .locator("label", { hasText: /^Men$/ })
        .locator("span.icon-uncheckbox")
        .first()
        .click();

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        "Product updated successfully",
    );

    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage.getByRole("paragraph").filter({ hasText: product.name }),
    ).toBeVisible();
}

async function createDownloadableProduct(adminPage) {
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
    await adminPage.locator('select[name="type"]').selectOption("downloadable");
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
     * Downloadable Links Section.
     */
    await adminPage.getByText("Add Link").first().click();
    await adminPage.waitForSelector(".min-h-0 > div > div");
    await adminPage.locator('input[name="title"]').first().fill(generateName());
    const linkTitle = await adminPage
        .locator('input[name="title"]')
        .inputValue();
    await adminPage.locator('input[name="price"]').first().fill("100");
    await adminPage.locator('input[name="downloads"]').fill("2");
    await adminPage.locator('select[name="type"]').selectOption("file");
    await adminPage
        .locator('input[name="file"]')
        .nth(1)
        .setInputFiles(path.resolve(__dirname, "../../data/images/1.webp"));
    await adminPage.locator('select[name="sample_type"]').selectOption("url");
    await adminPage.waitForSelector('input[name="sample_url"]');
    await adminPage
        .locator('input[name="sample_url"]')
        .fill(generateHostname());

    /**
     * Saving the Downloadable Link.
     */
    await adminPage.getByText("Link Save").click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await expect(adminPage.getByText(`${linkTitle}`)).toBeVisible();

    /**
     * Downloadable Samples Section.
     */
    await adminPage.getByText("Add Sample").first().click();
    await adminPage.waitForSelector(".min-h-0 > div > div");
    await adminPage.locator('input[name="title"]').fill(generateName());
    const sampleTitle = await adminPage
        .locator('input[name="title"]')
        .inputValue();
    await adminPage.locator('select[name="type"]').selectOption("url");
    await adminPage.locator('input[name="url"]').fill(generateHostname());

    /**
     * Saving the Downloadable Sample.
     */
    await adminPage.getByText("Link Save").click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await expect(adminPage.getByText(`${sampleTitle}`)).toBeVisible();

    /**
     * Saving the product.
     */
    await adminPage.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator("#app")).toContainText(
        "Product updated successfully",
    );
    /**
     * Checking the product in the list.
     */
    await adminPage.goto("admin/catalog/products");
    await expect(
        adminPage.getByRole("paragraph").filter({ hasText: product.name }),
    ).toBeVisible();
}

async function createBookingProduct(adminPage) {
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
        date: generateRandomDateTime(),
        location: generateLocation(),
    };

    const availableFromDate = new Date(); // Now
    const availableToDate = new Date(availableFromDate.getTime() + 60 * 60000);
    const formattedAvailableFromDate = availableFromDate
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
    const formattedAvailableToDate = availableToDate
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
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
    await adminPage.locator('select[name="type"]').selectOption("booking");
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
     * Default Booking Section.
     */
    //await adminPage.locator('select[name="booking[type]"]').selectOption("Default Booking");
    await adminPage
        .locator('input[name="booking[location]"]')
        .fill(product.location);

    //await adminPage.locator('input[name="booking[qty]"]').fill(product.weight);

    /**
     * Selecting Availablity Time frame.
     */
    await adminPage
        .locator('input[name="booking[available_from]"]')
        .fill(formattedAvailableFromDate);
    await adminPage
        .locator('input[name="booking[available_to]"]')
        .fill(formattedAvailableToDate);

    return product;
}

test.describe("simple product management", () => {
    /**
     * Verify group price while creating product
     */
    test("should update the product group price after delete", async ({
        adminPage,
    }) => {
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
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

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
            adminPage.getByText("For 022 Qty at fixed price of"),
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
            adminPage.getByText("For 020 Qty at discount of"),
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
            adminPage.getByText("For 015 Qty at fixed price of"),
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
            adminPage.getByText("For 022 Qty at fixed price of"),
        ).toBeVisible();
        await expect(
            adminPage.getByText("For 020 Qty at discount of"),
        ).not.toBeVisible();
        await expect(
            adminPage.getByText("For 015 Qty at fixed price of"),
        ).toBeVisible();
    });

    test("should create a simple product", async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
    });

    test("should edit a simple product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        const parent = adminPage
            .locator(
                ".flex.items-center.justify-between.gap-x-4 > .flex.items-center",
            )
            .first();

        await parent
            .locator(".cursor-pointer.icon-sort-right")
            .waitFor({ state: "visible" });
        await parent.locator(".cursor-pointer.icon-sort-right").click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        // Content will be added here. Currently just checking the general save button.

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        await expect(adminPage.locator("#app")).toContainText(
            /product updated successfully/i,
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Active")');

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

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" },
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

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

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    });
});

test.describe("configurable product management", () => {
    test("should create a configurable product", async ({ adminPage }) => {
        await createConfigurableProduct(adminPage);
    });

    test("should edit a configurable product", async ({ adminPage }) => {
        /**
         * Reaching to the products page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        /**
         * Opening the configurable product though edit button.
         */
        await adminPage
            .locator("div.flex.items-center.justify-between")
            .nth(7)
            .locator(".icon-sort-right")
            .click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * Editing the first varient product.
         */
        await adminPage.getByText("Edit", { exact: true }).first().click();
        await adminPage.locator('input[name="price"]').fill("50");
        await adminPage.locator('input[name="inventories\\[1\\]"]').fill("12");

        /**
         * Saving the varient product.
         */
        await adminPage
            .getByRole("button", { name: "Save", exact: true })
            .click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator("#app")).toContainText(
            "Product updated successfully",
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        await adminPage.waitForSelector(
            "div:nth-child(7) > .hidden.md\\:contents > .flex.gap-2\\.5 > .icon-uncheckbox",
            {
                state: "visible",
            },
        );
        await adminPage
            .locator(
                "div:nth-child(7) > .hidden.md\\:contents > .flex.gap-2\\.5 > .icon-uncheckbox",
            )
            .click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Active")');

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

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" },
        );

        await adminPage.waitForSelector(
            "div:nth-child(7) > .hidden.md\\:contents > .flex.gap-2\\.5 > .icon-uncheckbox",
            {
                state: "visible",
            },
        );
        await adminPage
            .locator(
                "div:nth-child(7) > .hidden.md\\:contents > .flex.gap-2\\.5 > .icon-uncheckbox",
            )
            .click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

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

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    });
});

test.describe("grouped product management", () => {
    test("should create a grouped product", async ({ adminPage }) => {
        await createGroupedProduct(adminPage);
    });

    test("should edit a grouped product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        const parent = adminPage
            .locator(
                ".flex.items-center.justify-between.gap-x-4 > .flex.items-center",
            )
            .first();

        await parent
            .locator(".cursor-pointer.icon-sort-right")
            .waitFor({ state: "visible" });
        await parent.locator(".cursor-pointer.icon-sort-right").click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * Deleting the first product.
         */
        const productName = await adminPage
            .getByText("Arctic Touchscreen Winter Gloves")
            .textContent();
        await adminPage.getByText("Delete", { exact: true }).first().click();
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
        });
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        /**
         * Expecting for the product should not be visible after delete.
         */
        await expect(adminPage.getByText(`${productName}`)).not.toBeVisible();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the groped product to be updated successfully.
         */
        await expect(adminPage.locator("#app")).toContainText(
            "Product updated successfully",
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Active")');

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

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" },
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

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

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    });
});

test.describe("virtual product management", () => {
    test("should create a virtual product", async ({ adminPage }) => {
        await createVirtualProduct(adminPage);
    });

    test("should edit a virtual product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        const parent = adminPage
            .locator(
                ".flex.items-center.justify-between.gap-x-4 > .flex.items-center",
            )
            .first();

        await parent
            .locator(".cursor-pointer.icon-sort-right")
            .waitFor({ state: "visible" });
        await parent.locator(".cursor-pointer.icon-sort-right").click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * Edit price, inverotries, description.
         */
        await adminPage.locator("#price").fill("100");
        await adminPage
            .locator('input[name="inventories\\[1\\]"]')
            .fill("1000");
        await adminPage
            .locator("#description_ifr")
            .contentFrame()
            .locator("html")
            .click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator("#app")).toContainText(
            "Product updated successfully",
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Active")');

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

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" },
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

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

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    });
});

test.describe("downloadable product management", () => {
    test("should create a downloadable product", async ({ adminPage }) => {
        await createDownloadableProduct(adminPage);
    });

    test("should edit a downloadable product", async ({ adminPage }) => {
        /**
         * Reaching to the edit product page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        const parent = adminPage
            .locator(
                ".flex.items-center.justify-between.gap-x-4 > .flex.items-center",
            )
            .first();

        await parent
            .locator(".cursor-pointer.icon-sort-right")
            .waitFor({ state: "visible" });
        await parent.locator(".cursor-pointer.icon-sort-right").click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
         * Edit price, edit downloadable links.
         */
        await adminPage.locator("#price").fill("100");
        await adminPage.getByText("Edit", { exact: true }).first().click();
        await adminPage.waitForSelector(".min-h-0 > div > div");
        await adminPage
            .locator('input[name="file"]')
            .nth(1)
            .setInputFiles(path.resolve(__dirname, "../../data/images/2.webp"));

        /**
         * Saving the downloadable link.
         */
        await adminPage
            .getByRole("button", { name: "Save", exact: true })
            .click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator("#app")).toContainText(
            "Product updated successfully",
        );
    });

    test("should mass update the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Active"), a:has-text("Disable")',
            { state: "visible", timeout: 1000 },
        );
        await adminPage.click('a:has-text("Active")');

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

        await expect(
            adminPage.getByText("Selected Products Updated Successfully"),
        ).toBeVisible();
    });

    test("should mass delete the products", async ({ adminPage }) => {
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
            { state: "visible" },
        );

        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 },
        );
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

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

        await expect(
            adminPage.getByText("Selected Products Deleted Successfully"),
        ).toBeVisible();
    });
});

test.describe("booking product management", () => {
    test.describe("booking product for default booking type", () => {
        test("should create default product with one booking for many days", async ({
            adminPage,
        }) => {
            const product = await createBookingProduct(adminPage);

            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            for (let slot = 1; slot <= 2; slot++) {
                await adminPage.getByText("Add Slots").first().click();

                await adminPage
                    .locator('select[name="from_day"]')
                    .selectOption((slot - 1).toString());

                await adminPage
                    .getByRole("textbox", { name: "From Time" })
                    .click();
                await adminPage.waitForTimeout(500);
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .click();
                await adminPage.waitForTimeout(500); // Adding a delay of 0.5 second

                await adminPage
                    .locator('select[name="to_day"]')
                    .selectOption(slot.toString());
                await adminPage
                    .getByRole("textbox", { name: "To Time" })
                    .click();
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .click();
                await adminPage.waitForTimeout(500);
                await adminPage.locator("body").press("Escape");
                await adminPage
                    .getByRole("button", { name: "Save", exact: true })
                    .click();
                await adminPage.locator(
                    `input[name="booking[slots][${slot}][id]`,
                );
                await expect(
                    adminPage.locator(
                        `input[name="booking[slots][${slot - 1}][id]"]`,
                    ),
                ).toHaveValue(/.+/);
            }

            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });

        test("should create default product with many booking for one day", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting default booking type with many booking for one day.
             */
            await adminPage
                .locator('select[name="booking\\[booking_type\\]"]')
                .selectOption("many");

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            let weeks = [
                {
                    name: "Sunday",
                    status: 1,
                },
                {
                    name: "Monday",
                    status: 2,
                },
                {
                    name: "Tuesday",
                    status: 3,
                },
            ];

            /**
             * Loop for slots for each day.
             */
            for (const day of weeks) {
                /**
                 * Clicking add button for adding Slots.
                 */
                await adminPage
                    .locator(
                        `.overflow-x-auto > div:nth-child(${day.status}) > div:nth-child(2) > .cursor-pointer`,
                    )
                    .first()
                    .click();

                /**
                 * Available from.
                 */
                const fromInput = adminPage.getByRole("textbox", {
                    name: "From",
                    exact: true,
                });
                await fromInput.click();
                await adminPage.waitForSelector(
                    ".flatpickr-calendar.hasTime.noCalendar.open",
                    { state: "visible" },
                );
                await adminPage
                    .getByRole("spinbutton", { name: "Hour" })
                    .click();
                await adminPage
                    .getByRole("spinbutton", { name: "Hour" })
                    .fill("10");
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .click();
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .fill("35");
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .press("Enter");

                /**
                 * Available To.
                 */
                const toInput = adminPage.getByRole("textbox", {
                    name: "To",
                    exact: true,
                });
                await toInput.click();
                await adminPage.waitForSelector(
                    ".flatpickr-calendar.hasTime.noCalendar.open",
                    { state: "visible" },
                );
                await adminPage
                    .getByRole("spinbutton", { name: "Hour" })
                    .click();
                await adminPage
                    .getByRole("spinbutton", { name: "Hour" })
                    .fill("20");
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .click();
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .fill("35");
                await adminPage
                    .getByRole("spinbutton", { name: "Minute" })
                    .press("Enter");

                /**
                 * Timeout of 0.5sec.
                 */
                await adminPage.waitForTimeout(500);

                /**
                 * Condition for choosing close on Sunday.
                 */
                if (day.name === "Sunday") {
                    await adminPage
                        .locator('select[name="status"]')
                        .selectOption("0");
                } else {
                    await adminPage
                        .locator('select[name="status"]')
                        .selectOption("1");
                }

                /**
                 * Saving the slot.
                 */
                await adminPage.locator("body").press("Escape");
                await adminPage
                    .getByRole("button", { name: "Save", exact: true })
                    .click();

                /**
                 * Expecting that the slot is successfully saved and should be visible under slot time duration
                 */
                await expect(
                    adminPage.locator(
                        `input[name="booking[slots][${day.status - 1}][0][id]"]`,
                    ),
                ).toHaveValue(/.+/);
            }

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the alert message as product updated successfully.
             */
            // await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

            /**
             * Expecting the product name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });
    });

    test.describe("booking product for appointment booking type", () => {
        test("should create appointment booking product that are not available every week with same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting appointment product.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("appointment");

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage
                .locator('select[name="booking[available_every_week]"]')
                .selectOption("0");

            /**
             * Selecting `Yes` in same slot for all days.
             */
            await adminPage
                .locator('select[name="booking[same_slot_all_days]"]')
                .selectOption("1");

            /**
             * Now adding slots with time duration.
             */
            await adminPage.getByText("Add Slots").first().click();

            /**
             * Slot 1 time available from.
             */
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Slot 1 time available to.
             */
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("55");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            /**
             * Adding slot 2 and waiting for time slot to be visible.
             */
            await adminPage.getByText("Add Slots").nth(2).click();
            await adminPage.waitForSelector('div.flex.gap-2\\.5[index="1"]', {
                state: "visible",
            });

            /**
             * Slot 2 time available from.
             */
            await adminPage
                .getByRole("textbox", { name: "From" })
                .nth(2)
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("11");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Slot 2 time available to.
             */
            await adminPage.getByRole("textbox", { name: "To" }).nth(2).click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("11");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Saving the slots.
             */
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Expecting that the slot is successfully saved and should be visible under slot time duration.
             */
            await expect(adminPage.getByText("10:35 - 10:55")).toBeVisible();
            await expect(adminPage.getByText("11:10 - 11:35")).toBeVisible();

            // await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);

            /**
             * Saving the Booking Product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the Alert Message as Product updated successfully.
             */
            // await adminPage.waitForSelector('text="Product updated successfully"');

            /**
             * Expecting the Product Name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });

        test("should create appointment booking product that are not available every week with no same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting appointment booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("appointment");

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage
                .locator('select[name="booking[available_every_week]"]')
                .selectOption("0");

            /**
             * Selecting `No` in same slot for all days.
             */
            await adminPage
                .locator('select[name="booking[same_slot_all_days]"]')
                .selectOption("0");

            let weeks = [
                {
                    name: "Sunday",
                    status: 0,
                    slots: 1,
                    fromHr: "10",
                    toHr: "19",
                },
                {
                    name: "Monday",
                    status: 1,
                    slots: 2,
                    fromHr: "07",
                    toHr: "12",
                },
                {
                    name: "Tuesday",
                    status: 2,
                    slots: 1,
                    fromHr: "09",
                    toHr: "20",
                },
            ];

            /**
             * Loop for creating multiple slots on each day.
             */
            for (const day of weeks) {
                /**
                 * Clicking add button for adding slots.
                 */
                await adminPage
                    .locator(
                        `.overflow-x-auto > div:nth-child(${
                            day.status + 1
                        }) > div:nth-child(2) > .cursor-pointer`,
                    )
                    .first()
                    .click();

                /**
                 * Loop for adding time slots.
                 */
                for (let slot = 0; slot < day.slots; slot++) {
                    /**
                     * Adding slots as per day available from.
                     */
                    await adminPage
                        .locator(
                            `div.flex.gap-2\\.5[index="${day.status}_${slot}"]`,
                        )
                        .focus();
                    await adminPage
                        .getByRole("textbox", { name: "From" })
                        .nth(slot + 1)
                        .click();
                    await adminPage.waitForSelector(
                        ".flatpickr-calendar.hasTime.noCalendar.open",
                        { state: "visible" },
                    );
                    await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .click();
                    const fromHrs = await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .fill(day.fromHr);
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .click();
                    const fromMin = await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .fill("00");

                    /**
                     * Adding slots as per day available to.
                     */
                    await adminPage
                        .getByRole("textbox", { name: "To" })
                        .nth(slot + 1)
                        .click();
                    await adminPage.waitForSelector(
                        ".flatpickr-calendar.hasTime.noCalendar.open",
                        { state: "visible" },
                    );
                    await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .click();
                    const toHrs = await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .fill(day.toHr);
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .click();
                    const toMin = await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .fill("55");

                    /**
                     * Clicking on Add Slots.
                     */
                    await adminPage.getByText("Add Slots").click();

                    // await expect(adminPage.locator(`input[name="booking[slots][${slot - 1}][id]"]`)).toHaveValue(/.+/);
                }
                /**
                 * Saving the Added time slots.
                 */
                await adminPage.locator("body").press("Escape");
                await adminPage
                    .getByRole("button", { name: "Save", exact: true })
                    .click();
            }

            /**
             * Expecting that the slot is successfully saved and should be visible under slot time duration.
             */
            //await expect(adminPage.getByText('10:00 - 19:55')).toBeVisible();
            //await expect(adminPage.getByText('07:00 - 12:55')).toBeVisible();
            //await expect(adminPage.getByText('09:00 - 20:55')).toBeVisible();
            //await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);

            /**
             * Saving the Booking Product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the Alert Message as Product updated successfully.
             */
            //  await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

            /**
             * Expecting the Product Name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });

        test("should create appointment booking product that are available every week with no same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting appointment booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("appointment");

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage
                .locator('select[name="booking[available_every_week]"]')
                .selectOption("1");

            /**
             * Selecting `No` in same slot for all days.
             */
            await adminPage
                .locator('select[name="booking[same_slot_all_days]"]')
                .selectOption("0");

            let weeks = [
                {
                    name: "Sunday",
                    status: 0,
                    slots: 1,
                    fromHr: "10",
                    toHr: "19",
                },
                {
                    name: "Monday",
                    status: 1,
                    slots: 2,
                    fromHr: "07",
                    toHr: "12",
                },
                {
                    name: "Tuesday",
                    status: 2,
                    slots: 1,
                    fromHr: "09",
                    toHr: "20",
                },
            ];

            /**
             * Loop for creating multiple slots on each day.
             */
            for (const day of weeks) {
                /**
                 * Clicking add button for adding slots.
                 */
                await adminPage
                    .locator(
                        `.overflow-x-auto > div:nth-child(${
                            day.status + 1
                        }) > div:nth-child(2) > .cursor-pointer`,
                    )
                    .first()
                    .click();

                /**
                 * Loop for adding time slots.
                 */
                for (let slot = 0; slot < day.slots; slot++) {
                    /**
                     * Adding slots as per day available from.
                     */
                    await adminPage
                        .locator(
                            `div.flex.gap-2\\.5[index="${day.status}_${slot}"]`,
                        )
                        .focus();
                    await adminPage
                        .getByRole("textbox", { name: "From" })
                        .nth(slot)
                        .click();
                    await adminPage.waitForSelector(
                        ".flatpickr-calendar.hasTime.noCalendar.open",
                        { state: "visible" },
                    );
                    await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .click();
                    const fromHrs = await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .fill(day.fromHr);
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .click();
                    const fromMin = await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .fill("00");
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .press("Enter");

                    /**
                     * Adding slots as per day available to.
                     */
                    await adminPage
                        .getByRole("textbox", { name: "To" })
                        .nth(slot)
                        .click();
                    await adminPage.waitForSelector(
                        ".flatpickr-calendar.hasTime.noCalendar.open",
                        { state: "visible" },
                    );
                    await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .click();
                    const toHrs = await adminPage
                        .getByRole("spinbutton", { name: "Hour" })
                        .fill(day.toHr);
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .click();
                    const toMin = await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .fill("55");
                    await adminPage
                        .getByRole("spinbutton", { name: "Minute" })
                        .press("Enter");

                    /**
                     * Clicking on Add Slots.
                     */
                    await adminPage.getByText("Add Slots").click();

                    // await expect(adminPage.locator(`input[name="booking[slots][${slot - 1}][id]"]`)).toHaveValue(/.+/);
                }
                /**
                 * Saving the Added time slots.
                 */
                await adminPage.locator("body").press("Escape");
                await adminPage
                    .getByRole("button", { name: "Save", exact: true })
                    .click();
            }

            /**
             * Expecting that the slot is successfully saved and should be visible under slot time duration.
             */
            await expect(adminPage.getByText("10:00 - 19:55")).toBeVisible();
            await expect(adminPage.getByText("09:00 - 20:55")).toBeVisible();
            //await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);

            /**
             * Saving the Booking Product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the Alert Message as Product updated successfully.
             */
            await adminPage.waitForSelector(
                'text="Product updated successfully"',
            );

            /**
             * Expecting the Product Name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });

        test("should create appointment booking product that are available every week with same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting appointment booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("appointment");

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage
                .locator('select[name="booking[available_every_week]"]')
                .selectOption("1");

            /**
             * Selecting `Yes` in same slot for all days.
             */
            await adminPage
                .locator('select[name="booking[same_slot_all_days]"]')
                .selectOption("1");

            /**
             * Now adding slots with time duration.
             */
            await adminPage.getByText("Add Slots").first().click();

            /**
             * Slot 1 time available from.
             */
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Slot 1 time available to.
             */
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("55");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Adding slot 2 and waiting for time slot to be visible.
             */
            await adminPage.getByText("Add Slots").nth(2).click();
            await adminPage.waitForSelector('div.flex.gap-2\\.5[index="1"]', {
                state: "visible",
            });

            /**
             * Slot 2 time available from.
             */
            //await adminPage.getByText('From To').nth(2).focus();
            await adminPage
                .getByRole("textbox", { name: "From" })
                .nth(1)
                .click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("11");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Slot 2 time available to.
             */
            await adminPage.getByRole("textbox", { name: "To" }).nth(1).click();
            await adminPage.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await adminPage.getByRole("spinbutton", { name: "Hour" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("11");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            /**
             * Saving the slots.
             */
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Expecting that the slot is successfully saved and should be visible under slot time duration.
             */
            await expect(adminPage.getByText("10:35 - 10:55")).toBeVisible();
            await expect(adminPage.getByText("11:10 - 11:35")).toBeVisible();
            // await expect(adminPage.locator(`input[name="booking[slots][0][id]"]`)).toHaveValue(/.+/);

            /**
             * Saving the Booking Product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the Alert Message as Product updated successfully.
             */
            await adminPage.waitForSelector(
                'text="Product updated successfully"',
            );

            /**
             * Expecting the Product Name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });
    });

    test.describe("booking product for event booking type", () => {
        test("should create event booking product ", async ({ adminPage }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting event booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("event");

            /**
             * Adding event location.
             */
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding Tickets
             */
            await adminPage.getByText("Add Tickets").click();
            await adminPage.getByRole("textbox", { name: "Name" }).click();
            await adminPage
                .getByRole("textbox", { name: "Name" })
                .fill(generateName());
            await adminPage.getByRole("textbox", { name: "Quantity" }).click();
            await adminPage
                .getByRole("textbox", { name: "Quantity" })
                .fill("2");
            await adminPage.getByRole("textbox", { name: "Price" }).click();
            await adminPage.getByRole("textbox", { name: "Price" }).fill("500");
            await adminPage
                .getByRole("textbox", { name: "Description" })
                .click();
            await adminPage
                .getByRole("textbox", { name: "Description" })
                .fill(generateDescription());
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the Booking Product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();

            /**
             * Expecting the Alert Message as Product updated successfully.
             */
            // await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

            /**
             * Expecting the Product Name to be visible.
             */
            await adminPage.goto("admin/catalog/products");
            await expect(
                adminPage
                    .getByRole("paragraph")
                    .filter({ hasText: product.name }),
            ).toBeVisible();
        });
    });

    test.describe("booking product for rental booking type", () => {
        test("should create rental booking product for daily basis with not available for every week", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "0" },
            );

            /**
             * Available From.
             */
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .fill("2027-04-08 16:00:00");
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .press("Enter");
            /**
             * Available To.
             */
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .fill("2027-04-25 18:00");
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .press("Enter");

            /**
             * select Daily renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("daily");
            await adminPage
                .getByRole("textbox", { name: "Daily Price" })
                .fill("3000");

            /**
             * Saving the booking product.
             */
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create rental booking product for daily basis with available for every week", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "1" },
            );

            /**
             * select Daily renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("daily");
            await adminPage
                .getByRole("textbox", { name: "Daily Price" })
                .fill("3000");

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create rental booking product for hourly basis available for same slot for All days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "1" },
            );

            /**
             * select Hourly renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("hourly");
            await adminPage
                .getByRole("textbox", { name: "Hourly Price" })
                .fill("300");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]"]')
                .selectOption("1");

            /**
             * Add slots
             */
            await adminPage.getByText("Add Slots").first().click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("20");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create rental booking product for hourly basis available for not same slot for All days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "1" },
            );

            /**
             * select Hourly renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("hourly");
            await adminPage
                .getByRole("textbox", { name: "Hourly Price" })
                .fill("300");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]"]')
                .selectOption("0");

            /**
             * Time slot .
             */
            await adminPage
                .locator(".overflow-x-auto > div > div > .cursor-pointer")
                .first()
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator(
                    ".overflow-x-auto > div:nth-child(2) > div > .cursor-pointer",
                )
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("09");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator("div:nth-child(3) > div > .cursor-pointer")
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create rental booking product for Both(hourly or day) basis available for same slot for All days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "1" },
            );

            /**
             * select Both(Hourly & Daily) renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("daily_hourly");
            await adminPage
                .getByRole("textbox", { name: "Daily Price" })
                .fill("3000");
            await adminPage
                .getByRole("textbox", { name: "Hourly Price" })
                .fill("300");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]"]')
                .selectOption("1");

            /**
             * Add slots
             */
            await adminPage.getByText("Add Slots").first().click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("20");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create rental booking product for Both(hourly or day) basis not available for same slot for All days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("rental");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Adding product quantity.
             */
            await adminPage
                .locator('input[name="booking[qty]"]')
                .fill(product.weight);

            /**
             * Selecting `Yes` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "1" },
            );

            /**
             * select Both(Hourly & Daily) renting Type.
             */
            await adminPage
                .locator('select[name="booking\\[renting_type\\]"]')
                .selectOption("daily_hourly");
            await adminPage
                .getByRole("textbox", { name: "Daily Price" })
                .fill("3000");
            await adminPage
                .getByRole("textbox", { name: "Hourly Price" })
                .fill("300");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]"]')
                .selectOption("0");

            /**
             * Time slot .
             */
            await adminPage
                .locator(".overflow-x-auto > div > div > .cursor-pointer")
                .first()
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator(
                    ".overflow-x-auto > div:nth-child(2) > div > .cursor-pointer",
                )
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("09");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator("div:nth-child(3) > div > .cursor-pointer")
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });
    });

    test.describe("booking product for table booking type", () => {
        test("should create Table booking product and charge per guest with same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("table");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "0" },
            );

            /**
             * Available from  .
             */
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .fill("2027-04-08 16:00:00");
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .press("Enter");
            /**
             * Available To  .
             */
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .fill("2027-04-25 18:00");
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .press("Enter");

            /**
             * charge per Guest
             */
            await adminPage
                .locator('select[name="booking\\[price_type\\]"]')
                .selectOption("guest");
            await adminPage
                .getByRole("textbox", { name: "Guest Capacity" })
                .fill("2");
            await adminPage
                .getByRole("textbox", { name: "Slot Duration (Mins)" })
                .fill("25");
            await adminPage
                .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
                .fill("10");
            await adminPage
                .getByRole("textbox", { name: "Prevent Scheduling Before" })
                .fill("0");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
                .selectOption("1");

            /**
             * Add slots
             */
            await adminPage.getByText("Add Slots").first().click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("20");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create Table booking product and charge per guest with not same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("table");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "0" },
            );

            /**
             * Available from.
             */
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .fill("2027-04-08 16:00:00");
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .press("Enter");
            /**
             * Available To
             */
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .fill("2027-04-25 18:00");
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .press("Enter");

            /**
             * charge per Guest
             */
            await adminPage
                .locator('select[name="booking\\[price_type\\]"]')
                .selectOption("guest");
            await adminPage
                .getByRole("textbox", { name: "Guest Capacity" })
                .fill("2");
            await adminPage
                .getByRole("textbox", { name: "Slot Duration (Mins)" })
                .fill("25");
            await adminPage
                .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
                .fill("10");
            await adminPage
                .getByRole("textbox", { name: "Prevent Scheduling Before" })
                .fill("0");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
                .selectOption("0");

            /**
             * Time slot .
             */
            await adminPage
                .locator(".overflow-x-auto > div > div > .cursor-pointer")
                .first()
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator(
                    ".overflow-x-auto > div:nth-child(2) > div > .cursor-pointer",
                )
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("09");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator("div:nth-child(3) > div > .cursor-pointer")
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create Table booking product and charge per table with same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("table");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "0" },
            );

            /**
             * Available from  .
             */
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .fill("2027-04-08 16:00:00");
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .press("Enter");
            /**
             * Available To  .
             */
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .fill("2027-04-25 18:00");
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .press("Enter");

            /**
             * charge per Table
             */
            await adminPage
                .locator('select[name="booking\\[price_type\\]"]')
                .selectOption("table");
            await adminPage
                .getByRole("textbox", { name: "Guest Limit Per Table" })
                .fill("4");
            await adminPage
                .getByRole("textbox", { name: "Guest Capacity" })
                .fill("3");
            await adminPage
                .getByRole("textbox", { name: "Slot Duration (Mins)" })
                .fill("25");
            await adminPage
                .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
                .fill("10");
            await adminPage
                .getByRole("textbox", { name: "Prevent Scheduling Before" })
                .fill("0");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
                .selectOption("1");

            /**
             * Add slots
             */
            await adminPage.getByText("Add Slots").first().click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("20");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage.getByRole("spinbutton", { name: "Minute" }).click();
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });

        test("should create Table booking product and charge per table with not same slot for all days", async ({
            adminPage,
        }) => {
            /**
             * Create the product.
             */
            const product = await createBookingProduct(adminPage);

            /**
             * Selecting Rental booking type.
             */
            await adminPage
                .locator('select[name="booking[type]"]')
                .selectOption("table");
            await adminPage
                .locator('input[name="booking[location]"]')
                .fill(product.location);

            /**
             * Selecting `No` in available every week.
             */
            await adminPage.selectOption(
                '//select[@name="booking[available_every_week]"]',
                { value: "0" },
            );

            /**
             * Available from.
             */
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .fill("2027-04-08 16:00:00");
            await adminPage
                .getByRole("textbox", { name: "Available From" })
                .press("Enter");
            /**
             * Available To
             */
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .fill("2027-04-25 18:00");
            await adminPage
                .getByRole("textbox", { name: "Available To" })
                .press("Enter");

            /**
             * charge per Table
             */
            await adminPage
                .locator('select[name="booking\\[price_type\\]"]')
                .selectOption("table");
            await adminPage
                .getByRole("textbox", { name: "Guest Limit Per Table" })
                .fill("4");
            await adminPage
                .getByRole("textbox", { name: "Guest Capacity" })
                .fill("3");
            await adminPage
                .getByRole("textbox", { name: "Slot Duration (Mins)" })
                .fill("25");
            await adminPage
                .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
                .fill("10");
            await adminPage
                .getByRole("textbox", { name: "Prevent Scheduling Before" })
                .fill("0");
            await adminPage
                .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
                .selectOption("0");

            /**
             * Time slot .
             */
            await adminPage
                .locator(".overflow-x-auto > div > div > .cursor-pointer")
                .first()
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator(
                    ".overflow-x-auto > div:nth-child(2) > div > .cursor-pointer",
                )
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("09");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("13");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await adminPage
                .locator("div:nth-child(3) > div > .cursor-pointer")
                .click();
            await adminPage
                .getByRole("textbox", { name: "From", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("14");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("45");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage
                .getByRole("textbox", { name: "To", exact: true })
                .click();
            await adminPage
                .getByRole("spinbutton", { name: "Hour" })
                .fill("18");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .fill("25");
            await adminPage
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await adminPage.locator("body").press("Escape");
            await adminPage
                .getByRole("button", { name: "Save", exact: true })
                .click();

            /**
             * Saving the booking product.
             */
            await adminPage
                .getByRole("button", { name: "Save Product" })
                .click();
        });
    });
});
