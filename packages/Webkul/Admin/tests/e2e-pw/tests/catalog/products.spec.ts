import { test, expect } from "../../setup";
import { fileURLToPath } from 'url';
import path from 'path';
import {
    generateSKU,
    generateName,
    generateDescription,
    generateHostname
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
    const name = await adminPage.locator('input[name="name"]').inputValue();
    console.log(name);

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

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

    /**
     * Checking the product in the list.
     */
    await adminPage.goto('admin/catalog/products');
    await expect(
        adminPage.getByText(`${name}`)
    ).toBeVisible();
};

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
    const name = await adminPage.locator('input[name="name"]').inputValue();

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

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

    /**
     * Checking the product in the list.
     */
    await adminPage.goto('admin/catalog/products');
    await expect(
        adminPage.getByText(`${name}`)
    ).toBeVisible();
};

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
    const name = await adminPage.locator('input[name="name"]').inputValue();

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
     * Adding products to make a group of products.
     */
    await adminPage.locator('.secondary-button').first().click();
    await adminPage.waitForSelector('p:has-text("Select Products")');
    await adminPage.getByRole('textbox', { name: 'Search by name' }).click();
    await adminPage.getByRole('textbox', { name: 'Search by name' }).fill('arc');
    await adminPage.locator('div').filter({ hasText: /^Arctic Touchscreen Winter GlovesSKU - SP-003\$21\.00100 Available$/ }).locator('label').click();
    await adminPage.locator('div').filter({ hasText: /^Arctic Warmth Wool Blend SocksSKU - SP-004\$21\.00100 Available$/ }).locator('label').click();
    await adminPage.locator('div').filter({ hasText: /^Arctic Bliss Stylish Winter ScarfSKU - SP-002\$17\.00100 Available$/ }).locator('label').click();

    /**
     * Saving the added product.
     */
    await adminPage.getByText('Add Selected Product').click();

    /**
     * Waiting for the products to be added.
     */
    await adminPage.waitForSelector('p:has-text("Arctic Touchscreen Winter Gloves")');
    await adminPage.waitForSelector('p:has-text("Arctic Warmth Wool Blend Socks")');
    await adminPage.waitForSelector('p:has-text("Arctic Bliss Stylish Winter")');

    /**
     * Saving the configurable product.
     */
    await adminPage.getByRole('button', { name: 'Save Product' }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

    /**
     * Checking the product in the list.
     */
    await adminPage.goto('admin/catalog/products');
    await expect(
        adminPage.getByText(`${name}`)
    ).toBeVisible();
};

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
        'button.primary-button:has-text("Create Product")'
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
    const name = await adminPage.locator('input[name="name"]').inputValue();

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
    await adminPage.getByRole('button', { name: 'Save Product' }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator('#app')).toContainText('Product updated successfully');

    /**
     * Checking the product in the list.
     */
    await adminPage.goto('admin/catalog/products');
    await expect(
        adminPage.getByText(`${name}`)
    ).toBeVisible();
};

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
        'button.primary-button:has-text("Create Product")'
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
    const name = await adminPage.locator('input[name="name"]').inputValue();

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
     * Downloadable Links Section.
     */
    await adminPage.getByText('Add Link').first().click();
    await adminPage.waitForSelector('.min-h-0 > div > div');
    await adminPage.locator('input[name="title"]').first().fill(generateName());
    const linkTitle = await adminPage.locator('input[name="title"]').inputValue();
    await adminPage.locator('input[name="price"]').first().fill('100');
    await adminPage.locator('input[name="downloads"]').fill('2');
    await adminPage.locator('select[name="type"]').selectOption('file');
    await adminPage.locator('input[name="file"]').nth(1).setInputFiles(path.resolve(__dirname, '../../data/images/1.webp'));
    await adminPage.locator('select[name="sample_type"]').selectOption('url');
    await adminPage.waitForSelector('input[name="sample_url"]');
    await adminPage.locator('input[name="sample_url"]').fill(generateHostname());

    /**
     * Saving the Downloadable Link.
     */
    await adminPage.getByText('Link Save').click();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
    await expect(adminPage.getByText(`${linkTitle}`)).toBeVisible();

    /**
     * Downloadable Samples Section.
     */
    await adminPage.getByText('Add Sample').first().click();
    await adminPage.waitForSelector('.min-h-0 > div > div');
    await adminPage.locator('input[name="title"]').fill(generateName());
    const sampleTitle = await adminPage.locator('input[name="title"]').inputValue();
    await adminPage.locator('select[name="type"]').selectOption('url');
    await adminPage.locator('input[name="url"]').fill(generateHostname());

    /**
     * Saving the Downloadable Sample.
     */
    await adminPage.getByText('Link Save').click();
    await adminPage.getByRole('button', { name: 'Save', exact: true }).click();
    await expect(adminPage.getByText(`${sampleTitle}`)).toBeVisible();

    /**
     * Saving the product.
     */
    await adminPage.getByRole('button', { name: 'Save Product' }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
    /**
     * Checking the product in the list.
     */
    await adminPage.goto('admin/catalog/products');
    await expect(
        adminPage.getByText(`${name}`)
    ).toBeVisible();

};

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

        await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
    });

    test("should create a configurable product", async ({ adminPage }) => {
        await createConfigurableProduct(adminPage);
    });

    test("should edit a configurable product", async ({ adminPage }) => {
        /**
         * Reaching to the products page.
         */
        await adminPage.goto("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")'
        );

        /**
         * Opening the configurable product though edit button.
         */
        await adminPage.locator('div:nth-child(7) > div:nth-child(3) > p > span:nth-child(2)').click();

        /**
         * Waiting for the main form to be visible.
         */
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');

        /**
        * Editing the first varient product.
        */
        await adminPage.getByText('Edit', { exact: true }).first().click();
        await adminPage.locator('input[name="price"]').fill('50');
        await adminPage.locator('input[name="inventories\\[1\\]"]').fill('12');

        /**
         * Saving the varient product.
         */
        await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
    });

    test("should create a grouped product", async ({ adminPage }) => {
        await createGroupedProduct(adminPage);
    });

    test("should edit a grouped product", async ({ adminPage }) => {
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

        /**
         * Deleting the first product.
         */
        const productName = await adminPage.getByText('Arctic Touchscreen Winter Gloves').textContent();
        await adminPage.getByText('Delete', { exact: true }).first().click();
        await adminPage.waitForSelector('text=Are you sure', { state: 'visible' });
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();

        /**
         * Expecting for the product should not be visible after delete.
         */
        await expect(
            adminPage.getByText(`${productName}`)
        ).not.toBeVisible();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the groped product to be updated successfully.
         */
        await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
    });

    test("should create a virtual product", async ({ adminPage }) => {
        await createVirtualProduct(adminPage);
    });

    test("should edit a virtual product", async ({ adminPage }) => {
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

        /**
         * Edit price, inverotries, description.
         */
        await adminPage.locator('#price').fill('100');
        await adminPage.locator('input[name="inventories\\[1\\]"]').fill('1000');
        await adminPage.locator('#description_ifr').contentFrame().locator('html').click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
    });

    test("should create a downloadable product", async ({ adminPage }) => {
        await createDownloadableProduct(adminPage);
    });

    test("should edit a downloadable product", async ({ adminPage }) => {
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

        /**
         * Edit price, edit downloadable links.
         */
        await adminPage.locator('#price').fill('100');
        await adminPage.getByText('Edit', { exact: true }).first().click();
        await adminPage.waitForSelector('.min-h-0 > div > div');
        await adminPage.locator('input[name="file"]').nth(1).setInputFiles(path.resolve(__dirname, '../../data/images/2.webp'));

        /**
         * Saving the downloadable link.
         */
        await adminPage.getByRole('button', { name: 'Save', exact: true }).click();

        /**
         * Saving the product.
         */
        await adminPage.getByRole('button', { name: 'Save Product' }).click();

        /**
         * Expecting for the product to be saved.
         */
        await expect(adminPage.locator('#app')).toContainText('Product updated successfully');
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