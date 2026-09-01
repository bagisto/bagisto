import { expect } from "../setup";
import { loginAsCustomer, addAddress } from "../utils/customer";
import {
    generateName,
    generateSKU,
    generateDescription,
    generateHostname,
} from "../utils/faker";

export async function generateOrder(page) {
    await loginAsCustomer(page);

    await addAddress(page);

    await page.goto("");
    await page.waitForLoadState("networkidle");
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
    await page.waitForTimeout(2000);
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

    await page.waitForSelector("text=Free Shipping");
    await page.getByText("Free Shipping").first().click();
    await page.waitForTimeout(2000);

    await page.waitForSelector("text=Cash On Delivery");
    await page.getByText("Cash On Delivery").first().click();
    await page.waitForTimeout(2000);

    await page.getByRole("button", { name: "Place Order" }).click();
    await page.waitForTimeout(2000);
    await page.waitForSelector("text=Thank you for your order!");
    await expect(page.locator("text=Thank you for your order!")).toBeVisible();
}

export async function downloadableOrder(page) {
    const product = {
        name: generateName(),
        sku: generateSKU(),
        productNumber: generateSKU(),
        shortDescription: generateDescription(),
        description: generateDescription(),
        price: "199",
    };

    await page.goto("admin/catalog/products");
    await page.waitForSelector(
        'button.primary-button:has-text("Create Product")'
    );
    await page.getByRole("button", { name: "Create Product" }).click();

    await page.locator('select[name="type"]').selectOption("downloadable");
    await page.locator('select[name="attribute_family_id"]').selectOption("1");
    await page.locator('input[name="sku"]').fill(generateSKU());
    await page.getByRole("button", { name: "Save Product" }).click();

    await page.waitForSelector(
        'button.primary-button:has-text("Save Product")'
    );

    await page.waitForSelector('form[enctype="multipart/form-data"]');

    await page.locator("#product_number").fill(product.productNumber);
    await page.locator("#name").fill(product.name);
    const name = await page.locator('input[name="name"]').inputValue();

    await page.fillInTinymce(
        "#short_description_ifr",
        product.shortDescription
    );
    await page.fillInTinymce("#description_ifr", product.description);

    await page.locator("#meta_title").fill(product.name);
    await page.locator("#meta_keywords").fill(product.name);
    await page.locator("#meta_description").fill(product.shortDescription);

    await page.locator("#price").fill(product.price);

    await page.getByText("Add Link").first().click();
    await page.waitForSelector(".min-h-0 > div > div");
    await page.locator('input[name="title"]').fill(generateName());
    const linkTitle = await page.locator('input[name="title"]').inputValue();
    await page.locator('input[name="price"]').first().fill("100");
    await page.locator('input[name="downloads"]').fill("10");
    await page.locator('select[name="type"]').selectOption("url");
    await page.waitForSelector('input[name="url"]');
    await page.locator('input[name="url"]').fill(generateHostname());
    await page.locator('select[name="sample_type"]').selectOption("url");
    await page.waitForSelector('input[name="sample_url"]');
    await page.locator('input[name="sample_url"]').fill(generateHostname());

    await page.getByText("Link Save").click();
    await page.getByRole("button", { name: "Save", exact: true }).click();
    await expect(page.getByText(`${linkTitle}`)).toBeVisible();

    await page.getByRole("button", { name: "Save Product" }).click();

    await expect(page.locator("#app")).toContainText(
        "Product updated successfully"
    );

    await page.goto("admin/catalog/products");
    await expect(
        page.locator("p.break-all.text-base").filter({ hasText: product.name })
    ).toBeVisible();

    await loginAsCustomer(page);

    await addAddress(page);

    await page.goto("");
    await page.getByRole("textbox", { name: "Search products here" }).click();
    await page
        .getByRole("textbox", { name: "Search products here" })
        .fill(product.name);
    await page
        .getByRole("textbox", { name: "Search products here" })
        .press("Enter");
    await page.waitForTimeout(2000);
    await page.getByRole("button", { name: "Add To Cart" }).click();
    await page.waitForTimeout(3000);
    await page.locator("#main label").nth(1).click();
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

    await page.locator('label[for="moneytransfer"]').first().click();
    await page.waitForTimeout(2000);

    await page.getByRole("button", { name: "Place Order" }).click();
    await page.waitForTimeout(2000);
    await page.waitForSelector("text=Thank you for your order!");
    await expect(page.locator("text=Thank you for your order!")).toBeVisible();

    await page.goto("admin/sales/orders");
    await page.locator(".row > div:nth-child(4) > a").first().click();
    await page.getByText("Invoice", { exact: true }).click();
    await page.locator("#can_create_transaction").nth(1).click();
    await page.getByRole("button", { name: "Create Invoice" }).click();
    await expect(
        page.getByText("Invoice created successfully Close")
    ).toBeVisible();

    return product.name;
}
