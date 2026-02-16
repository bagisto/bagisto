import { test, expect } from "../setup";
import { ProductCreation } from "../pages/product";
import { ProductCheckout } from "../pages/checkout-flow";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { RMACreation } from "../pages/rma";
import address from "../utils/address";
import {
    generateFirstName,
    generateLastName,
    generateEmail,
    generatePhoneNumber,
    generateDescription,
    generateName,
    generateRandomNumericString,
    generateSKU,
    generateLocation,
    generateRandomDateTime,
} from "../utils/faker";
import { fileURLToPath } from "url";
import path from "path";
import fs from "fs";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

function saveGeneratedProductName(productName: string) {
    fs.writeFileSync(
        "generatedProductName.json",
        JSON.stringify({ productName }, null, 2),
    );
}

function getGeneratedProductName(): string {
    const data = JSON.parse(
        fs.readFileSync("generatedProductName.json", "utf-8"),
    );
    return data.productName;
}

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
     * save generated name
     */
    saveGeneratedProductName(product.name);

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
     * save generated name
     */
    saveGeneratedProductName(product.name);

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
        .filter({ hasText: "Black" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "White" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Orange" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Blue" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Pink" })
        .locator("span")
        .click();
    await adminPage
        .getByRole("paragraph")
        .filter({ hasText: "Purple" })
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
        shortDescription: "test",
        description: "test",
        price: "199",
        weight: "25",
    };

    /**
     * save generated name
     */
    saveGeneratedProductName(product.name);

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
        .fill("simple");

   await adminPage
    .locator("div.flex.justify-between.gap-2\\.5.border-b", {
        has: adminPage.locator("p", {
            hasText: "simple-",
        }),
    })
    .first()
    .locator('input[type="checkbox"]')
    .check({ force: true });

    /**
     * Saving the added product.
     */
    await adminPage.getByText("Add Selected Product").click();

    /**
     * Waiting for the products to be added.
     */
    await adminPage.waitForSelector(
        'p:has-text("simple")',
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
     * save generated name
     */
    saveGeneratedProductName(product.name);

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
     * save generated name
     */
    saveGeneratedProductName(product.name);

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
    await adminPage.locator('select[name="type"]').selectOption("url");
    await adminPage
        .locator('input[name="url"]')
        .fill("https://bagisto.com/en/");
    await adminPage.locator('select[name="sample_type"]').selectOption("url");
    await adminPage
        .locator('input[name="sample_url"]')
        .fill("https://bagisto.com/en/");

    /**
     * Saving the Downloadable Link.
     */
    await adminPage.getByText("Link Save").click();
    await adminPage.getByRole("button", { name: "Save", exact: true }).click();
    await expect(adminPage.getByText(`${linkTitle}`)).toBeVisible();

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

    /**
     * save generated name
     */
    saveGeneratedProductName(product.name);

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

export async function generateSimpleOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        const productName = getGeneratedProductName();
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill(productName);

        /**
         * Use for simple product selection
         */
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
    /**
     * Billing address selection or creation
     */
    const billingRadios = await adminPage.$$('input[name="billing.id"]');
    if (billingRadios.length > 0) {
        const addressLabels = await adminPage.$$(
            `input[name="billing.id"] + label`,
        );
        const randomIndex = Math.floor(Math.random() * billingRadios.length);
        await addressLabels[randomIndex].click();
    } else {
        await adminPage.click(
            "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all",
        );
        if ((await address(adminPage)) !== "done") return;
    }

    const useForShipping = await adminPage.$(
        'input[name="billing.use_for_shipping"]',
    );
    const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
    const isShippingChecked = await useForShipping?.isChecked();

    if (shouldUseBilling !== isShippingChecked) {
        await adminPage.click('input[name="billing.use_for_shipping"] + label');
    }

    /**
     * Shipping address logic (if different from billing)
     */
    if (!shouldUseBilling) {
        const shippingRadios = await adminPage.$$('input[name="shipping.id"]');
        if (shippingRadios.length > 0) {
            const shippingLabels = await adminPage.$$(
                `input[name="shipping.id"] + label`,
            );
            const randomIndex = Math.floor(
                Math.random() * shippingRadios.length,
            );
            await shippingLabels[randomIndex].click();
        } else {
            await adminPage.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible",
            );

            await adminPage.fill(
                'input[name="shipping.company_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.first_name"]',
                generateFirstName(),
            );
            await adminPage.fill(
                'input[name="shipping.last_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.email"]',
                generateEmail(),
            );
            await adminPage.fill(
                'input[name="shipping.address.[0]"]',
                generateFirstName(),
            );
            await adminPage.selectOption(
                'select[name="shipping.country"]',
                "IN",
            );
            await adminPage.selectOption('select[name="shipping.state"]', "UP");
            await adminPage.fill(
                'input[name="shipping.city"]',
                generateLastName(),
            );
            await adminPage.fill('input[name="shipping.postcode"]', "201301");
            await adminPage.fill(
                'input[name="shipping.phone"]',
                generatePhoneNumber(),
            );
            await adminPage.press('input[name="shipping.phone"]', "Enter");
        }
    }

    /**
     * shipping method
     */
    await adminPage.click(
        ".mt-4.flex.justify-end > button.primary-button:visible",
    );

    const shippingMethods = await adminPage
        .waitForSelector('input[name="shipping_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (shippingMethods) {
        const options = await adminPage.$$(
            'input[name="shipping_method"] + label',
        );
        await options[Math.floor(Math.random() * options.length)].click();
    }

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

export async function generateConfigurableOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        const productName = getGeneratedProductName();
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill(productName);

        /**
         * Use for simple product selection
         */
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
    await adminPage
        .locator('select[name="super_attribute[23]"]')
        .selectOption("1");
    await adminPage
        .locator('select[name="super_attribute[24]"]')
        .selectOption("6");
    await adminPage
        .locator("#steps-container")
        .getByRole("button", { name: "Add to Cart" })
        .click();
    await expect(
        adminPage.getByText(/Product added to cart successfully/).first(),
    ).toBeVisible();
    /**
     * Billing address selection or creation
     */
    const billingRadios = await adminPage.$$('input[name="billing.id"]');
    if (billingRadios.length > 0) {
        const addressLabels = await adminPage.$$(
            `input[name="billing.id"] + label`,
        );
        const randomIndex = Math.floor(Math.random() * billingRadios.length);
        await addressLabels[randomIndex].click();
    } else {
        await adminPage.click(
            "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all",
        );
        if ((await address(adminPage)) !== "done") return;
    }

    const useForShipping = await adminPage.$(
        'input[name="billing.use_for_shipping"]',
    );
    const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
    const isShippingChecked = await useForShipping?.isChecked();

    if (shouldUseBilling !== isShippingChecked) {
        await adminPage.click('input[name="billing.use_for_shipping"] + label');
    }

    /**
     * Shipping address logic (if different from billing)
     */
    if (!shouldUseBilling) {
        const shippingRadios = await adminPage.$$('input[name="shipping.id"]');
        if (shippingRadios.length > 0) {
            const shippingLabels = await adminPage.$$(
                `input[name="shipping.id"] + label`,
            );
            const randomIndex = Math.floor(
                Math.random() * shippingRadios.length,
            );
            await shippingLabels[randomIndex].click();
        } else {
            await adminPage.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible",
            );

            await adminPage.fill(
                'input[name="shipping.company_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.first_name"]',
                generateFirstName(),
            );
            await adminPage.fill(
                'input[name="shipping.last_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.email"]',
                generateEmail(),
            );
            await adminPage.fill(
                'input[name="shipping.address.[0]"]',
                generateFirstName(),
            );
            await adminPage.selectOption(
                'select[name="shipping.country"]',
                "IN",
            );
            await adminPage.selectOption('select[name="shipping.state"]', "UP");
            await adminPage.fill(
                'input[name="shipping.city"]',
                generateLastName(),
            );
            await adminPage.fill('input[name="shipping.postcode"]', "201301");
            await adminPage.fill(
                'input[name="shipping.phone"]',
                generatePhoneNumber(),
            );
            await adminPage.press('input[name="shipping.phone"]', "Enter");
        }
    }

    /**
     * shipping method
     */
    await adminPage.click(
        ".mt-4.flex.justify-end > button.primary-button:visible",
    );

    const shippingMethods = await adminPage
        .waitForSelector('input[name="shipping_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (shippingMethods) {
        const options = await adminPage.$$(
            'input[name="shipping_method"] + label',
        );
        await options[Math.floor(Math.random() * options.length)].click();
    }

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await adminPage.waitForLoadState("networkidle");
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

export async function generateGroupOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        const productName = getGeneratedProductName();
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill(productName);

        /**
         * Use for simple product selection
         */
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
    await adminPage.waitForTimeout(3000);
    await adminPage
        .locator("#steps-container")
        .getByRole("button", { name: "Add to Cart" })
        .click();
    await expect(
        adminPage.getByText(/Product added to cart successfully/).first(),
    ).toBeVisible();
    /**
     * Billing address selection or creation
     */
    const billingRadios = await adminPage.$$('input[name="billing.id"]');
    if (billingRadios.length > 0) {
        const addressLabels = await adminPage.$$(
            `input[name="billing.id"] + label`,
        );
        const randomIndex = Math.floor(Math.random() * billingRadios.length);
        await addressLabels[randomIndex].click();
    } else {
        await adminPage.click(
            "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all",
        );
        if ((await address(adminPage)) !== "done") return;
    }

    const useForShipping = await adminPage.$(
        'input[name="billing.use_for_shipping"]',
    );
    const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
    const isShippingChecked = await useForShipping?.isChecked();

    if (shouldUseBilling !== isShippingChecked) {
        await adminPage.click('input[name="billing.use_for_shipping"] + label');
    }

    /**
     * Shipping address logic (if different from billing)
     */
    if (!shouldUseBilling) {
        const shippingRadios = await adminPage.$$('input[name="shipping.id"]');
        if (shippingRadios.length > 0) {
            const shippingLabels = await adminPage.$$(
                `input[name="shipping.id"] + label`,
            );
            const randomIndex = Math.floor(
                Math.random() * shippingRadios.length,
            );
            await shippingLabels[randomIndex].click();
        } else {
            await adminPage.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible",
            );

            await adminPage.fill(
                'input[name="shipping.company_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.first_name"]',
                generateFirstName(),
            );
            await adminPage.fill(
                'input[name="shipping.last_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.email"]',
                generateEmail(),
            );
            await adminPage.fill(
                'input[name="shipping.address.[0]"]',
                generateFirstName(),
            );
            await adminPage.selectOption(
                'select[name="shipping.country"]',
                "IN",
            );
            await adminPage.selectOption('select[name="shipping.state"]', "UP");
            await adminPage.fill(
                'input[name="shipping.city"]',
                generateLastName(),
            );
            await adminPage.fill('input[name="shipping.postcode"]', "201301");
            await adminPage.fill(
                'input[name="shipping.phone"]',
                generatePhoneNumber(),
            );
            await adminPage.press('input[name="shipping.phone"]', "Enter");
        }
    }

    /**
     * shipping method
     */
    await adminPage.click(
        ".mt-4.flex.justify-end > button.primary-button:visible",
    );

    const shippingMethods = await adminPage
        .waitForSelector('input[name="shipping_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (shippingMethods) {
        const options = await adminPage.$$(
            'input[name="shipping_method"] + label',
        );
        await options[Math.floor(Math.random() * options.length)].click();
    }

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await adminPage.waitForLoadState("networkidle");
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

export async function generateVirtualOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        const productName = getGeneratedProductName();
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill(productName);

        /**
         * Use for simple product selection
         */
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
    await expect(
        adminPage.getByText(/Product added to cart successfully/).first(),
    ).toBeVisible();

    await adminPage.getByText("Add Address").click();

    await adminPage.fill(
        'input[name="billing.company_name"]',
        generateLastName(),
    );
    await adminPage.fill(
        'input[name="billing.first_name"]',
        generateFirstName(),
    );
    await adminPage.fill('input[name="billing.last_name"]', generateLastName());
    await adminPage.fill('input[name="billing.email"]', generateEmail());
    await adminPage.fill(
        'input[name="billing.address.[0]"]',
        generateFirstName(),
    );
    await adminPage.selectOption('select[name="billing.country"]', "IN");
    await adminPage.selectOption('select[name="billing.state"]', "UP");
    await adminPage.fill('input[name="billing.city"]', generateLastName());
    await adminPage.fill('input[name="billing.postcode"]', "201301");
    await adminPage.fill('input[name="billing.phone"]', generatePhoneNumber());
    await adminPage.press('input[name="billing.phone"]', "Enter");
    await adminPage.getByRole("button", { name: "Proceed" }).click();

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

export async function generateDownloadableOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        const productName = getGeneratedProductName();
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill(productName);

        /**
         * Use for simple product selection
         */
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
        await adminPage.waitForTimeout(3000);
        await adminPage.locator("#steps-container label").nth(1).click();

        await adminPage
            .locator("#steps-container")
            .getByRole("button", { name: "Add to Cart" })
            .click();
        await expect(
            adminPage.getByText(/Product added to cart successfully/).first(),
        ).toBeVisible();
    }

    await adminPage.getByText("Add Address").click();

    await adminPage.fill(
        'input[name="billing.company_name"]',
        generateLastName(),
    );
    await adminPage.fill(
        'input[name="billing.first_name"]',
        generateFirstName(),
    );
    await adminPage.fill('input[name="billing.last_name"]', generateLastName());
    await adminPage.fill('input[name="billing.email"]', generateEmail());
    await adminPage.fill(
        'input[name="billing.address.[0]"]',
        generateFirstName(),
    );
    await adminPage.selectOption('select[name="billing.country"]', "IN");
    await adminPage.selectOption('select[name="billing.state"]', "UP");
    await adminPage.fill('input[name="billing.city"]', generateLastName());
    await adminPage.fill('input[name="billing.postcode"]', "201301");
    await adminPage.fill('input[name="billing.phone"]', generatePhoneNumber());
    await adminPage.press('input[name="billing.phone"]', "Enter");
    await adminPage.getByRole("button", { name: "Proceed" }).click();

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

export async function generateOrder(adminPage) {
    await adminPage.goto("admin/sales/orders");
    await adminPage.click("button.primary-button:visible");
    await adminPage.click(
        "div.flex.flex-col.items-center > button.secondary-button:visible",
    );

    /**
     * Fill customer details
     */
    await adminPage.fill(
        'input[name="first_name"]:visible',
        generateFirstName(),
    );
    await adminPage.fill('input[name="last_name"]:visible', generateLastName());
    await adminPage.fill('input[name="email"]:visible', generateEmail());
    await adminPage.fill('input[name="phone"]:visible', generatePhoneNumber());
    await adminPage.selectOption('select[name="gender"]:visible', "Other");
    await adminPage.press('input[name="phone"]:visible', "Enter");

    /**
     * selecting product
     */
    const productSelector =
        ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
    const itemExists = await adminPage
        .waitForSelector(productSelector, { timeout: 5000 })
        .catch(() => null);

    if (itemExists) {
        const items = await adminPage.$$(productSelector);
        const randomItem = items[Math.floor(Math.random() * items.length)];
        await randomItem.click();
        await adminPage.click("button.primary-button:visible");
    } else {
        await adminPage.click(
            "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
        );
        await adminPage
            .getByRole("textbox", { name: "Search by name" })
            .fill("omni");

        /**
         * Use for simple product selection
         */
        const searchResult = await adminPage
            .waitForSelector(
                "button.cursor-pointer.text-sm.text-blue-600.transition-all",
                { timeout: 5000 },
            )
            .catch(() => null);

        if (searchResult) {
            await adminPage
                .locator("//button[normalize-space()='Add To Cart']")
                .first()
                .click();
        }
    }

    /**
     * Billing address selection or creation
     */
    const billingRadios = await adminPage.$$('input[name="billing.id"]');
    if (billingRadios.length > 0) {
        const addressLabels = await adminPage.$$(
            `input[name="billing.id"] + label`,
        );
        const randomIndex = Math.floor(Math.random() * billingRadios.length);
        await addressLabels[randomIndex].click();
    } else {
        await adminPage.click(
            "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all",
        );
        if ((await address(adminPage)) !== "done") return;
    }

    const useForShipping = await adminPage.$(
        'input[name="billing.use_for_shipping"]',
    );
    const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
    const isShippingChecked = await useForShipping?.isChecked();

    if (shouldUseBilling !== isShippingChecked) {
        await adminPage.click('input[name="billing.use_for_shipping"] + label');
    }

    /**
     * Shipping address logic (if different from billing)
     */
    if (!shouldUseBilling) {
        const shippingRadios = await adminPage.$$('input[name="shipping.id"]');
        if (shippingRadios.length > 0) {
            const shippingLabels = await adminPage.$$(
                `input[name="shipping.id"] + label`,
            );
            const randomIndex = Math.floor(
                Math.random() * shippingRadios.length,
            );
            await shippingLabels[randomIndex].click();
        } else {
            await adminPage.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible",
            );

            await adminPage.fill(
                'input[name="shipping.company_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.first_name"]',
                generateFirstName(),
            );
            await adminPage.fill(
                'input[name="shipping.last_name"]',
                generateLastName(),
            );
            await adminPage.fill(
                'input[name="shipping.email"]',
                generateEmail(),
            );
            await adminPage.fill(
                'input[name="shipping.address.[0]"]',
                generateFirstName(),
            );
            await adminPage.selectOption(
                'select[name="shipping.country"]',
                "IN",
            );
            await adminPage.selectOption('select[name="shipping.state"]', "UP");
            await adminPage.fill(
                'input[name="shipping.city"]',
                generateLastName(),
            );
            await adminPage.fill('input[name="shipping.postcode"]', "201301");
            await adminPage.fill(
                'input[name="shipping.phone"]',
                generatePhoneNumber(),
            );
            await adminPage.press('input[name="shipping.phone"]', "Enter");
        }
    }

    /**
     * shipping method
     */
    await adminPage.click(
        ".mt-4.flex.justify-end > button.primary-button:visible",
    );

    const shippingMethods = await adminPage
        .waitForSelector('input[name="shipping_method"] + label', {
            timeout: 10000,
        })
        .catch(() => null);

    if (shippingMethods) {
        const options = await adminPage.$$(
            'input[name="shipping_method"] + label',
        );
        await options[Math.floor(Math.random() * options.length)].click();
    }

    await adminPage.locator("label", { hasText: "Money Transfer" }).click();

    const nextBtn = await adminPage.$$(
        "button.primary-button.w-max.px-11.py-3",
    );
    await nextBtn[nextBtn.length - 1].click();
    await expect(adminPage.getByText("Order Items")).toBeVisible();
}

test.describe("rma management", () => {
    test.setTimeout(240000);
    test.beforeEach(
        "should create simple product for checkout to create rma",
        async ({ adminPage }) => {
            const productCreation = new ProductCreation(adminPage);

            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${Date.now()}`,
                name: `Simple-${Date.now()}`,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
        },
    );

    test("should allow checkout and RMA creation so the admin can accept it", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();

        const rmaCreation = new RMACreation(shopPage);
        await rmaCreation.rmaCreation();
    });

    test("should allow admin to accept rma", async ({ adminPage }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminAcceptRMA();
    });

    test("should allow checkout and RMA creation so the admin can decline it", async ({
        shopPage,
    }) => {
        await loginAsCustomer(shopPage);
        await addAddress(shopPage);

        const productCheckout = new ProductCheckout(shopPage);
        await productCheckout.customerCheckout();

        const rmaCreation = new RMACreation(shopPage);
        await rmaCreation.rmaCreation();
    });

    test("should allow admin to declined rma", async ({ adminPage }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminDeclinedRMA();
    });
});

test.describe(" rma management ", () => {
    test("should allow admin to create reason for rma", async ({
        adminPage,
    }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminCraeteRMAReason();
    });

    test("should allow admin to create rule for rma", async ({ adminPage }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminCraeteRMARules();
    });

    test("should allow admin to create status for rma", async ({
        adminPage,
    }) => {
        const rmaCreation = new RMACreation(adminPage);
        await rmaCreation.adminCraeteRMAStatus();
    });
});

test.describe("sales management", () => {
    test("should be able to create orders", async ({ adminPage }) => {
        await createSimpleProduct(adminPage);
        await generateSimpleOrder(adminPage);
    });

    test("should be comment on order", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();

        const lorem100 = generateDescription(200);
        adminPage.fill('textarea[name="comment"]', lorem100);
        await adminPage
            .locator('span.icon-uncheckbox.cursor-pointer[role="button"]')
            .click();

        await adminPage.getByRole("button", { name: "Submit Comment" }).click();
        await expect(adminPage.locator("#app")).toContainText(
            "Comment added successfully.",
        );
    });

    test("should be able to reorder", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");
        await adminPage.waitForTimeout(3000);
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.getByRole("link", { name: " Reorder" }).click();

        await expect(adminPage.getByText("Cart Items")).toBeVisible();
        await adminPage.locator("label.icon-radio-normal").first().click();
        await adminPage.getByRole("button", { name: "Proceed" }).click();
        await adminPage.getByText("Free Shipping$0.00Free").click();
        await adminPage
            .locator("label")
            .filter({ hasText: "Cash On Delivery" })
            .click();
        await adminPage.getByRole("button", { name: "Place Order" }).click();
        await expect(adminPage.locator("#app")).toContainText("Pending");
    });

    test("should be able to create invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-sales.text-2xl:visible",
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-sales.text-2xl:visible",
        );

        await adminPage.click('button[type="submit"].primary-button:visible');
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice created successfully",
        );
    });

    test("should be create shipment", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");

        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        const exists = await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-ship.text-2xl:visible",
                { timeout: 1000 },
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-ship.text-2xl:visible",
        );

        await adminPage.fill(
            'input[name="shipment[carrier_title]"]',
            generateName(),
        );
        await adminPage.fill(
            'input[name="shipment[track_number]"]',
            generateRandomNumericString(),
        );

        await adminPage
            .locator('[id="shipment\\[source\\]"]')
            .selectOption("1");

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(adminPage.locator("#app")).toContainText(
            "Shipment created successfully",
        );
    });

    test("should be able to create refund", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/orders");
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-cancel.text-2xl:visible",
                { timeout: 1000 },
            )
            .catch(() => null);

        await adminPage
            .locator("div.transparent-button.px-1 > .icon-cancel.text-2xl")
            .click();
        await adminPage
            .waitForSelector(
                'input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible',
                { timeout: 1000 },
            )
            .catch(() => null);

        const itemQty = await adminPage.$$(
            'input[type="text"].w-full.rounded-md.border.px-3.text-sm.text-gray-600.transition-all:visible',
        );
        let i = 1;
        for (let element of itemQty) {
            await element.scrollIntoViewIfNeeded();

            if (i > itemQty.length - 2) {
                let rand = Math.floor(Math.random() * 2000);
                await element.fill(rand.toString());
            }

            if (i > itemQty.length - 3) {
                continue;
            }

            const currentValue = await element.inputValue();

            const maxQty = parseInt(currentValue, 10);
            const qty = Math.floor(Math.random() * (maxQty - 1)) + 1;

            await element.fill(qty.toString());

            i++;
        }

        await adminPage.click('button[type="submit"].primary-button:visible');

        await expect(
            adminPage.locator("p", { hasText: "Refund created successfully" }),
        ).toBeVisible();
    });

    test("should be create mail invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/invoices");

        await adminPage.waitForSelector(
            ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view",
        );
        await adminPage
            .locator(
                ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view",
            )
            .first()
            .click();

        await adminPage
            .getByRole("button", { name: " Send Duplicate Invoice" })
            .click();
        await adminPage
            .getByRole("button", { name: "Send", exact: true })
            .click();
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice sent successfully",
        );
    });

    test("should be able to print invoice", async ({ adminPage }) => {
        await adminPage.goto("admin/sales/invoices");

        await adminPage.waitForSelector(
            ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view",
        );
        await adminPage
            .locator(
                ".cursor-pointer.rounded-md.text-2xl.transition-all.icon-view",
            )
            .first()
            .click();

        const downloadPromise = adminPage.waitForEvent("download");
        await adminPage.getByRole("link", { name: " Print" }).click();
        const download = await downloadPromise;
    });

    test.describe("should able to cancel product", () => {
        test("should be able to cancel simple order", async ({ adminPage }) => {
            await createSimpleProduct(adminPage);
            /**
             * create order
             */
            await generateSimpleOrder(adminPage);

            /**
             * Should Cancel a Order
             */
            await adminPage.getByRole("link", { name: "Sales" }).click();

            await adminPage
                .locator(".flex.items-center.justify-between > a")
                .first()
                .click();
            await adminPage.getByRole("link", { name: "Cancel" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(adminPage.locator("#app")).toContainText(
                "Order cancelled successfully",
            );
        });

        test("should be able to cancel configurable order", async ({
            adminPage,
        }) => {
            await createConfigurableProduct(adminPage);
            /**
             * create order
             */
            await generateConfigurableOrder(adminPage);

            /**
             * Should Cancel a Order
             */
            await adminPage.waitForTimeout(2000);
            await adminPage.getByRole("link", { name: "Sales" }).click();

            await adminPage
                .locator(".flex.items-center.justify-between > a")
                .first()
                .click();
            await adminPage.getByRole("link", { name: "Cancel" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(adminPage.locator("#app")).toContainText(
                "Order cancelled successfully",
            );
        });

        test("should be able to cancel group order", async ({ adminPage }) => {
            await createSimpleProduct(adminPage);
            await createGroupedProduct(adminPage);
            /**
             * create order
             */
            await generateGroupOrder(adminPage);

            /**
             * Should Cancel a Order
             */
            await adminPage.waitForLoadState("networkidle");
            await adminPage.getByRole("link", { name: "Sales" }).click();
            await adminPage.goto("admin/sales/orders");

            await adminPage
                .locator(".flex.items-center.justify-between > a")
                .first()
                .click();
            await adminPage.getByRole("link", { name: "Cancel" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(adminPage.locator("#app")).toContainText(
                "Order cancelled successfully",
            );
        });

        test("should be able to cancel virtual order", async ({
            adminPage,
        }) => {
            await createVirtualProduct(adminPage);
            /**
             * create order
             */
            await generateVirtualOrder(adminPage);

            /**
             * Should Cancel a Order
             */
            await adminPage.waitForLoadState("networkidle");
            await adminPage.getByRole("link", { name: "Sales" }).click();
            await adminPage.goto("admin/sales/orders");

            await adminPage
                .locator(".flex.items-center.justify-between > a")
                .first()
                .click();
            await adminPage.getByRole("link", { name: "Cancel" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(adminPage.locator("#app")).toContainText(
                "Order cancelled successfully",
            );
        });

        test("should be able to cancel downloadable order", async ({
            adminPage,
        }) => {
            await createDownloadableProduct(adminPage);
            /**
             * create order
             */
            await generateDownloadableOrder(adminPage);

            /**
             * Should Cancel a Order
             */
            await adminPage.getByRole("link", { name: "Sales" }).click();

            await adminPage
                .locator(".flex.items-center.justify-between > a")
                .first()
                .click();
            await adminPage.getByRole("link", { name: "Cancel" }).click();
            await adminPage
                .getByRole("button", { name: "Agree", exact: true })
                .click();
            await expect(adminPage.locator("#app")).toContainText(
                "Order cancelled successfully",
            );
        });
    });

    test("should be able to create transaction", async ({ adminPage }) => {
        /**
         * create order
         */
        await createSimpleProduct(adminPage);
        await generateSimpleOrder(adminPage);
        await adminPage.waitForTimeout(3000);

        /**
         * Create Transaction
         */
        await adminPage.goto("admin/sales/orders");
        await adminPage.waitForTimeout(3000);
        await adminPage.reload();
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage.locator(".transparent-button > .icon-sales").click();
        await adminPage.locator("#can_create_transaction").nth(1).click();
        await adminPage.getByRole("button", { name: "Create Invoice" }).click();

        /**
         * Go to transaction page
         */
        await adminPage.goto("admin/sales/transactions");
        await expect(adminPage.getByText("Paid").first()).toBeVisible();
    });

    test("support mass status Change  to Paid for Invoices", async ({
        adminPage,
    }) => {
        await createSimpleProduct(adminPage);
        await generateSimpleOrder(adminPage);
        await adminPage.waitForTimeout(5000);

        /**
         * create invoice
         */
        await adminPage.goto("admin/sales/orders");
        await adminPage.reload();
        await adminPage.waitForTimeout(5000);
        await adminPage.locator(".row > div:nth-child(4) > a").first().click();
        await adminPage
            .waitForSelector(
                "div.transparent-button.px-1 > .icon-sales.text-2xl:visible",
            )
            .catch(() => null);

        await adminPage.click(
            "div.transparent-button.px-1 > .icon-sales.text-2xl:visible",
        );

        await adminPage.click('button[type="submit"].primary-button:visible');
        await expect(adminPage.locator("#app")).toContainText(
            "Invoice created successfully",
        );

        /**
         * Go to invoice page
         */
        await adminPage.goto("admin/sales/invoices");

        const checkboxes = await adminPage.locator(".icon-uncheckbox");
        await checkboxes.first().click();
        await adminPage
            .getByRole("button", { name: "Select Action " })
            .click();
        await adminPage.getByRole("link", { name: "Update Status " }).hover();
        await adminPage.getByRole("link", { name: "Paid" }).click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        await expect(adminPage.locator("#app")).toContainText("Paid");
        await expect(
            adminPage.getByText("Selected invoice updated successfully"),
        ).toBeVisible();
    });

    test("support mass status Change to overdue for Invoices", async ({
        adminPage,
    }) => {
        /**
         * Go to invoice page
         */
        await adminPage.goto("admin/sales/invoices");

        const checkboxes = await adminPage.locator(".icon-uncheckbox");
        await checkboxes.first().click();
        await adminPage
            .getByRole("button", { name: "Select Action " })
            .click();
        await adminPage.getByRole("link", { name: "Update Status " }).hover();
        await adminPage.getByRole("link", { name: "Overdue" }).click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        await expect(adminPage.locator("#app")).toContainText("Overdue");
        await expect(
            adminPage.getByText("Selected invoice updated successfully"),
        ).toBeVisible();
    });
});
