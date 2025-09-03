import { expect } from "../setup";
import { loginAsCustomer, addAddress } from "../utils/customer";
import {
    generateName,
    generateSKU,
    generateDescription,
    generateHostname,
} from "../utils/faker";

export async function generateOrder(page) {
    /**
     * Customer login.
     */
    await loginAsCustomer(page);

    /**
     * Fill customer default address.
     */
    await addAddress(page);

    /**
     * Go to the shop to buy a product.
     */
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .waitFor({ state: "visible" });

    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .locator("button")
        .first()
        .click();
    await expect(page.locator("#app")).toContainText("Item Added Successfully");
    await page.locator(".icon-cancel").first().click();
    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("link", { name: "Continue to Checkout" }).click();
    await page
        .locator(
            'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
        )
        .nth(0)
        .click();
    await page.getByRole("button", { name: "Proceed" }).click();
    await page.waitForTimeout(2000);

    /**
     * Choose shipping method.
     */
    await page.waitForSelector("text=Free Shipping");
    await page.getByText("Free Shipping").first().click();
    await page.waitForTimeout(2000);

    /**
     * Choose payment option.
     */
    await page.waitForSelector("text=Cash On Delivery");
    await page.getByText("Cash On Delivery").first().click();
    await page.waitForTimeout(2000);

    /**
     * Place order.
     */
    await page.getByRole("button", { name: "Place Order" }).click();
    await page.waitForTimeout(2000);
    await page.waitForSelector("text=Thank you for your order!");
    await expect(page.locator("text=Thank you for your order!")).toBeVisible();
}

export async function downloadableOrder(page) {
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
    await page.goto("admin/catalog/products");
    await page.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await page.getByRole("button", { name: "Create Product" }).click();

    /**
     * Opening create product form in modal.
     */
    await page.locator('select[name="type"]').selectOption("downloadable");
    await page.locator('select[name="attribute_family_id"]').selectOption("1");
    await page.locator('input[name="sku"]').fill(generateSKU());
    await page.getByRole("button", { name: "Save Product" }).click();

    /**
     * After creating the product, the page is redirected to the edit product page, where
     * all the details need to be filled in.
     */
    await page.waitForSelector(
        'button.primary-button:has-text("Save Product")'
    );

    /**
     * Waiting for the main form to be visible.
     */
    await page.waitForSelector('form[enctype="multipart/form-data"]');

    /**
     * General Section.
     */
    await page.locator("#product_number").fill(product.productNumber);
    await page.locator("#name").fill(product.name);
    const name = await page.locator('input[name="name"]').inputValue();

    /**
     * Description Section.
     */
    await page.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
    );
    await page.fillInTinymce("#description_ifr", product.description);

    /**
     * Meta Description Section.
     */
    await page.locator("#meta_title").fill(product.name);
    await page.locator("#meta_keywords").fill(product.name);
    await page.locator("#meta_description").fill(product.shortDescription);

    /**
     * Image Section.
     */
    // Will add images later.

    /**
     * Price Section.
     */
    await page.locator("#price").fill(product.price);

    /**
     * Downloadable Links Section.
     */
    await page.getByText("Add Link").first().click();
    await page.waitForSelector(".min-h-0 > div > div");
    await page.locator('input[name="title"]').fill(generateName());
    const linkTitle = await page.locator('input[name="title"]').inputValue();
    await page.locator('input[name="price"]').first().fill("100");
    await page.locator('input[name="downloads"]').fill("10");
    await page.locator('select[name="type"]').selectOption('url');
    await page.waitForSelector('input[name="url"]');
    await page.locator('input[name="url"]').fill(generateHostname());
    await page.locator('select[name="sample_type"]').selectOption("url");
    await page.waitForSelector('input[name="sample_url"]');
    await page.locator('input[name="sample_url"]').fill(generateHostname());

    /**
     * Saving the Downloadable Link.
     */
    await page.getByText("Link Save").click();
    await page.getByRole("button", { name: "Save", exact: true }).click();
    await expect(page.getByText(`${linkTitle}`)).toBeVisible();

    /**
     * Saving the product.
     */
    await page.getByRole("button", { name: "Save Product" }).click();

    /**
     * Expecting for the product to be saved.
     */
    await expect(page.locator("#app")).toContainText(
        "Product updated successfully"
    );
    /**
     * Checking the product in the list.
     */
    await page.goto("admin/catalog/products");
    await expect(page.locator('p.break-all.text-base').filter({ hasText: product.name })).toBeVisible();

    /**
     * Customer login.
     */
    await loginAsCustomer(page);

    /**
     * Fill customer default address.
     */
    await addAddress(page);

    /**
     * customer to buy a product
     */
    await page.goto("");
    await page
        .locator("#main div")
        .filter({ hasText: "New Products View All New" })
        .getByLabel(product.name)
        .click();
    await page.locator("#main label").nth(2).click();
    await page.getByRole("button", { name: "Add To Cart" }).click();
    await expect(
        page
            .getByRole("paragraph")
            .filter({ hasText: "Item Added Successfully" })
    ).toBeVisible();

    await page.getByRole("button", { name: "Shopping Cart" }).click();
    await page.getByRole("link", { name: "Continue to Checkout" }).click();
    await page
        .locator(
            'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]'
        )
        .nth(0)
        .click();
    await page.getByRole("button", { name: "Proceed" }).click();
    await page.waitForTimeout(2000);
    
    /**
     * Choose payment option.
     */
    await expect(page.locator('label').filter({ hasText: 'Money Transfer' })).toBeVisible();
    await page.locator('.relative > .icon-radio-unselect').first().click();
    await page.waitForTimeout(2000);

    /**
     * Place order.
     */
    await page.getByRole("button", { name: "Place Order" }).click();
    await page.waitForTimeout(2000);
    await page.waitForSelector("text=Thank you for your order!");
    await expect(page.locator("text=Thank you for your order!")).toBeVisible();

    /**
     * completing the order.
     */
    await page.goto("admin/sales/orders");
    await page.locator(".row > div:nth-child(4) > a").first().click();
    await page.getByText('Invoice', { exact: true }).click();
    await page.locator('#can_create_transaction').nth(1).click();
    await page.getByRole('button', { name: 'Create Invoice' }).click();
    await expect(page.getByText('Invoice created successfully Close')).toBeVisible();

    return product.name;
}
