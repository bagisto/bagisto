import fs from "fs";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateDescription,
    generateEmail,
    generateFirstName,
    generateHostname,
    generateLastName,
    generateLocation,
    generateName,
    generatePhoneNumber,
    generateRandomDateTime,
    generateRandomNumericString,
    generateSKU,
} from "../../../utils/faker";
import address from "../../../utils/address";

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

export class SalesCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private async openCreateProductModal() {
        await this.visit("admin/catalog/products");
        await this.page.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        await this.page.getByRole("button", { name: "Create Product" }).click();
    }

    private async startProductCreation(type: string) {
        await this.openCreateProductModal();
        await this.page.locator('select[name="type"]').selectOption(type);
        await this.page
            .locator('select[name="attribute_family_id"]')
            .selectOption({ label: "Clothing" });
        await this.page.locator('input[name="sku"]').fill(generateSKU());
        await this.page.getByRole("button", { name: "Save Product" }).click();
    }

    private async waitForProductEditForm() {
        await this.page.waitForSelector(
            'button.primary-button:has-text("Save Product")',
        );
        await this.page.waitForSelector('form[enctype="multipart/form-data"]');
    }

    private async createSimpleProductInternal(enableRma: boolean) {
        const product = {
            name: `simple-${Date.now()}`,
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
        };

        saveGeneratedProductName(product.name);

        await this.startProductCreation("simple");
        await this.waitForProductEditForm();
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator("#price").fill(product.price);
        await this.page.locator("#weight").fill(product.weight);
        await this.page
            .locator('input[name="inventories\\[1\\]"]')
            .fill("5000");

        if (enableRma) {
            await this.page.locator('label[for="allow_rma"]').click();
        }

        await this.page.getByRole("button", { name: "Save Product" }).click();
        await expect(this.page.locator("#app")).toContainText(
            /product updated successfully/i,
        );
        await this.visit("admin/catalog/products");
        await expect(
            this.page
                .locator("p.break-all.text-base")
                .filter({ hasText: product.name }),
        ).toBeVisible();
    }

    async createSimpleProduct() {
        await this.createSimpleProductInternal(false);
    }

    async createRmaEnabledSimpleProduct() {
        await this.createSimpleProductInternal(true);
    }

    async createConfigurableProduct() {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
        };

        saveGeneratedProductName(product.name);

        await this.startProductCreation("configurable");
        await this.page.waitForSelector(
            'p:has-text("Configurable Attributes")',
        );

        for (const color of [
            "Red",
            "Black",
            "White",
            "Orange",
            "Blue",
            "Pink",
            "Purple",
            "Green",
            "Yellow",
            "Grey",
            "Dual Tone",
        ]) {
            await this.page
                .getByRole("paragraph")
                .filter({ hasText: color })
                .locator("span")
                .click();
        }

        for (let i = 0; i < 6; i++) {
            await this.page
                .locator("div:nth-child(2) > div > p > .icon-cross")
                .first()
                .click();
        }

        await this.page.getByRole("button", { name: "Save Product" }).click();
        await this.page.waitForSelector(
            'p:has-text("Product created successfully")',
        );
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Prices").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page
            .getByRole("paragraph")
            .filter({ hasText: "Edit Prices" })
            .click();
        await this.page.locator('input[name="price"]').fill("100");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();

        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Inventories").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page.locator('input[name="inventories\\[1\\]"]').fill("010");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();

        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Weight").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page.locator('input[name="weight"]').fill("01");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.page.getByRole("button", { name: "Save Product" }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Product updated successfully",
        );
    }

    async createGroupedProduct() {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: "test",
            description: "test",
        };

        saveGeneratedProductName(product.name);

        await this.startProductCreation("grouped");
        await this.waitForProductEditForm();
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator(".secondary-button").first().click();
        await this.page.waitForSelector('p:has-text("Select Products")');
        await this.page
            .getByRole("textbox", { name: "Search by name" })
            .fill("simple");
        await this.page
            .locator("div.flex.justify-between.gap-2\\.5.border-b", {
                has: this.page.locator("p", { hasText: "simple-" }),
            })
            .first()
            .locator('input[type="checkbox"]')
            .check({ force: true });
        await this.page.getByText("Add Selected Product").click();
        await this.page.waitForSelector('p:has-text("simple")');
        await this.page.getByRole("button", { name: "Save Product" }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Product updated successfully",
        );
    }

    async createVirtualProduct() {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
        };

        saveGeneratedProductName(product.name);

        await this.startProductCreation("virtual");
        await this.waitForProductEditForm();
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator("#price").fill(product.price);
        await this.page
            .locator('input[name="inventories\\[1\\]"]')
            .fill("5000");
        await this.page.getByRole("button", { name: "Save Product" }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Product updated successfully",
        );
    }

    async createDownloadableProduct() {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
        };

        saveGeneratedProductName(product.name);

        await this.startProductCreation("downloadable");
        await this.waitForProductEditForm();
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator("#price").fill(product.price);
        await this.page.getByText("Add Link").first().click();
        await this.page.waitForSelector(".min-h-0 > div > div");
        await this.page
            .locator('input[name="title"]')
            .first()
            .fill(generateName());
        const linkTitle = await this.page
            .locator('input[name="title"]')
            .inputValue();
        await this.page.locator('input[name="price"]').first().fill("100");
        await this.page.locator('input[name="downloads"]').fill("2");
        await this.page.locator('select[name="type"]').selectOption("url");
        await this.page
            .locator('input[name="url"]')
            .fill("https://bagisto.com/en/");
        await this.page
            .locator('select[name="sample_type"]')
            .selectOption("url");
        await this.page
            .locator('input[name="sample_url"]')
            .fill("https://bagisto.com/en/");
        await this.page.getByText("Link Save").click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(this.page.getByText(linkTitle)).toBeVisible();
        await this.page.getByRole("button", { name: "Save Product" }).click();
        await expect(this.page.locator("#app")).toContainText(
            "Product updated successfully",
        );
    }

    async createBookingProduct() {
        const product = {
            name: generateName(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            location: generateLocation(),
        };

        saveGeneratedProductName(product.name);

        const availableFromDate = new Date();
        const availableToDate = new Date(
            availableFromDate.getTime() + 60 * 60000,
        );
        const formattedAvailableFromDate = availableFromDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");
        const formattedAvailableToDate = availableToDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        await this.startProductCreation("booking");
        await this.waitForProductEditForm();
        await this.page.locator("#product_number").fill(product.productNumber);
        await this.page.locator("#name").fill(product.name);
        await (this.page as any).fillInTinymce(
            "#short_description_ifr",
            product.shortDescription,
        );
        await (this.page as any).fillInTinymce(
            "#description_ifr",
            product.description,
        );
        await this.page.locator("#meta_title").fill(product.name);
        await this.page.locator("#meta_keywords").fill(product.name);
        await this.page
            .locator("#meta_description")
            .fill(product.shortDescription);
        await this.page.locator("#price").fill(product.price);
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[available_from]"]')
            .fill(formattedAvailableFromDate);
        await this.page
            .locator('input[name="booking[available_to]"]')
            .fill(formattedAvailableToDate);
    }

    async generateSimpleOrder() {
        await this.visit("admin/sales/orders");
        await this.page.click("button.primary-button:visible");
        await this.page.click(
            "div.flex.flex-col.items-center > button.secondary-button:visible",
        );

        await this.page.fill(
            'input[name="first_name"]:visible',
            generateFirstName(),
        );
        await this.page.fill(
            'input[name="last_name"]:visible',
            generateLastName(),
        );
        await this.page.fill('input[name="email"]:visible', generateEmail());
        await this.page.fill(
            'input[name="phone"]:visible',
            generatePhoneNumber(),
        );
        await this.page.selectOption('select[name="gender"]:visible', "Other");
        await this.page.press('input[name="phone"]:visible', "Enter");

        const productSelector =
            ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
        const itemExists = await this.page
            .waitForSelector(productSelector, { timeout: 5000 })
            .catch(() => null);

        if (itemExists) {
            const items = await this.page.$$(productSelector);
            const randomItem = items[Math.floor(Math.random() * items.length)];
            await randomItem.click();
            await this.page.click("button.primary-button:visible");
        } else {
            await this.page.click(
                "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
            );
            const productName = getGeneratedProductName();
            await this.page
                .getByRole("textbox", { name: "Search by name" })
                .fill(productName);
            await this.page
                .getByRole("button", { name: "Add To Cart" })
                .first()
                .click();
        }

        await this.handleOrderAddresses(true);
        await this.completeOrderFlow();
    }

    async generateConfigurableOrder() {
        await this.generateBaseProductOrder(true, async () => {
            await this.page
                .locator("select.custom-select")
                .nth(0) // adjust index based on position
                .selectOption({ label: "Brown" });
            await this.page
                .locator("select.custom-select")
                .nth(1) // adjust index based on position
                .selectOption({ label: "Full" });
            await this.page
                .getByRole("button", { name: "Add To Cart" })
                .first()
                .click();
            await expect(
                this.page
                    .getByText(/Product added to cart successfully/)
                    .first(),
            ).toBeVisible();
        });
    }

    async generateGroupOrder() {
        await this.generateBaseProductOrder(true, async () => {
            await this.page.waitForTimeout(3000);
            await this.page
                .locator("#steps-container")
                .getByRole("button", { name: "Add to Cart" })
                .click();
            await expect(
                this.page
                    .getByText(/Product added to cart successfully/)
                    .first(),
            ).toBeVisible();
        });
    }

    async generateVirtualOrder() {
        await this.generateBaseProductOrder(false, async () => {
            await expect(
                this.page
                    .getByText(/Product added to cart successfully/)
                    .first(),
            ).toBeVisible();
        });
    }

    async generateDownloadableOrder() {
        await this.generateBaseProductOrder(false, async () => {
            await this.page.locator('input[name="links[]"]').first().click({ force: true });
            await this.page
                .getByRole("button", { name: "Add To Cart" })
                .first()
                .click();
            await expect(
                this.page
                    .getByText(/Product added to cart successfully/)
                    .first(),
            ).toBeVisible();
        });
    }

    private async generateBaseProductOrder(
        handleShipping: boolean,
        afterSelection?: () => Promise<void>,
    ) {
        await this.visit("admin/sales/orders");
        await this.page.click("button.primary-button:visible");
        await this.page.click(
            "div.flex.flex-col.items-center > button.secondary-button:visible",
        );

        await this.page.fill(
            'input[name="first_name"]:visible',
            generateFirstName(),
        );
        await this.page.fill(
            'input[name="last_name"]:visible',
            generateLastName(),
        );
        await this.page.fill('input[name="email"]:visible', generateEmail());
        await this.page.fill(
            'input[name="phone"]:visible',
            generatePhoneNumber(),
        );
        await this.page.selectOption('select[name="gender"]:visible', "Other");
        await this.page.press('input[name="phone"]:visible', "Enter");

        const productSelector =
            ".grid > div.mt-2.flex > .cursor-pointer.text-emerald-600.transition-all";
        const itemExists = await this.page
            .waitForSelector(productSelector, { timeout: 5000 })
            .catch(() => null);

        if (itemExists) {
            const items = await this.page.$$(productSelector);
            const randomItem = items[Math.floor(Math.random() * items.length)];
            await randomItem.click();
            await this.page.click("button.primary-button:visible");
        } else {
            await this.page.click(
                "p.flex.flex-col.gap-1.text-base.font-semibold + button.secondary-button",
            );
            const productName = getGeneratedProductName();
            await this.page
                .getByRole("textbox", { name: "Search by name" })
                .fill(productName);
            await this.page
                .getByRole("button", { name: "Add To Cart" })
                .first()
                .click();
        }

        if (afterSelection) {
            await afterSelection();
        }

        await this.handleOrderAddresses(handleShipping);
        await this.completeOrderFlow();
    }

    private async handleOrderAddresses(handleShipping: boolean) {
        const billingRadios = await this.page.$$('input[name="billing.id"]');
        if (billingRadios.length > 0) {
            const addressLabels = await this.page.$$(
                `input[name="billing.id"] + label`,
            );
            const randomIndex = Math.floor(
                Math.random() * billingRadios.length,
            );
            await addressLabels[randomIndex].click();
        } else {
            await this.page.click(
                "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all",
            );
            if ((await address(this.page)) !== "done") return;
        }

        if (!handleShipping) {
            return;
        }

        const useForShipping = await this.page.$(
            'input[name="billing.use_for_shipping"]',
        );
        const shouldUseBilling = Math.floor(Math.random() * 20) % 3 !== 1;
        const isShippingChecked = await useForShipping?.isChecked();

        if (shouldUseBilling !== isShippingChecked) {
            await this.page.click(
                'input[name="billing.use_for_shipping"] + label',
            );
        }

        if (!shouldUseBilling) {
            const shippingRadios = await this.page.$$(
                'input[name="shipping.id"]',
            );
            if (shippingRadios.length > 0) {
                const shippingLabels = await this.page.$$(
                    `input[name="shipping.id"] + label`,
                );
                const randomIndex = Math.floor(
                    Math.random() * shippingRadios.length,
                );
                await shippingLabels[randomIndex].click();
            } else {
                await this.page.click(
                    "p.text-base.font-medium.text-gray-600 + p.cursor-pointer.text-blue-600.transition-all:visible",
                );
                await this.page.fill(
                    'input[name="shipping.company_name"]',
                    generateLastName(),
                );
                await this.page.fill(
                    'input[name="shipping.first_name"]',
                    generateFirstName(),
                );
                await this.page.fill(
                    'input[name="shipping.last_name"]',
                    generateLastName(),
                );
                await this.page.fill(
                    'input[name="shipping.email"]',
                    generateEmail(),
                );
                await this.page.fill(
                    'input[name="shipping.address.[0]"]',
                    generateFirstName(),
                );
                await this.page.selectOption(
                    'select[name="shipping.country"]',
                    "IN",
                );
                await this.page.selectOption(
                    'select[name="shipping.state"]',
                    "UP",
                );
                await this.page.fill(
                    'input[name="shipping.city"]',
                    generateLastName(),
                );
                await this.page.fill(
                    'input[name="shipping.postcode"]',
                    "201301",
                );
                await this.page.fill(
                    'input[name="shipping.phone"]',
                    generatePhoneNumber(),
                );
                await this.page.press('input[name="shipping.phone"]', "Enter");
            }
        }
    }

    private async completeOrderFlow() {
        await this.page.click(
            ".mt-4.flex.justify-end > button.primary-button:visible",
        );

        const shippingMethods = await this.page
            .waitForSelector('input[name="shipping_method"] + label', {
                timeout: 10000,
            })
            .catch(() => null);

        if (shippingMethods) {
            const options = await this.page.$$(
                'input[name="shipping_method"] + label',
            );
            await options[Math.floor(Math.random() * options.length)].click();
        }

        await this.page.locator("label", { hasText: "Money Transfer" }).click();

        const nextBtn = await this.page.$$(
            "button.primary-button.w-max.px-11.py-3",
        );
        await nextBtn[nextBtn.length - 1].click();
        await this.page.waitForLoadState("networkidle");
        await expect(this.page.getByText("Order Items")).toBeVisible();
    }
}
