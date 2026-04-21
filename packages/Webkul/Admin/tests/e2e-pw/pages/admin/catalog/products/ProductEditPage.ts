import { fileURLToPath } from "url";
import path from "path";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { ProductListPage } from "./ProductListPage";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export interface ProductEditData {
    productNumber?: string;
    name?: string;
    shortDescription?: string;
    description?: string;
    metaTitle?: string;
    metaKeywords?: string;
    metaDescription?: string;
    price?: string;
    weight?: string;
    inventory?: string;
}

export class ProductEditPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get form() {
        return this.page.locator('form[enctype="multipart/form-data"]');
    }

    private get productNumberInput() {
        return this.page.locator("#product_number");
    }

    private get skuInput() {
        return this.page.locator("#sku");
    }

    private get nameInput() {
        return this.page.locator("#name");
    }

    private get metaTitleInput() {
        return this.page.locator("#meta_title");
    }

    private get metaKeywordsInput() {
        return this.page.locator("#meta_keywords");
    }

    private get metaDescriptionInput() {
        return this.page.locator("#meta_description");
    }

    private get priceInput() {
        return this.page.locator("#price");
    }

    private get weightInput() {
        return this.page.locator("#weight");
    }

    private get inventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    async waitForForm() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.saveButton).toBeVisible();
        await expect(this.skuInput).toHaveValue(/.+/);
    }

    async openProductForEdit() {
        const productListPage = new ProductListPage(this.page);

        await productListPage.openProductForEdit();
        await this.waitForForm();
    }

    async fillGeneralDetails(data: ProductEditData) {
        if (data.productNumber) {
            await this.productNumberInput.fill(data.productNumber);
        }

        if (data.name) {
            await this.nameInput.fill(data.name);
        }
    }

    async fillDescriptions(shortDescription?: string, description?: string) {
        if (shortDescription) {
            await (this.page as any).fillInTinymce(
                "#short_description_ifr",
                shortDescription,
            );
        }

        if (description) {
            await (this.page as any).fillInTinymce(
                "#description_ifr",
                description,
            );
        }
    }

    async fillMeta(data: ProductEditData) {
        if (data.metaTitle) {
            await this.metaTitleInput.fill(data.metaTitle);
        }

        if (data.metaKeywords) {
            await this.metaKeywordsInput.fill(data.metaKeywords);
        }

        if (data.metaDescription) {
            await this.metaDescriptionInput.fill(data.metaDescription);
        }
    }

    async fillPrice(price: string) {
        await this.priceInput.fill(price);
    }

    async fillWeight(weight: string) {
        await this.weightInput.fill(weight);
    }

    async fillInventory(quantity: string) {
        await this.inventoryInput.click();
        await this.inventoryInput.fill(quantity);
    }

    async saveProduct() {
        await this.saveButton.click();
    }

    async saveAndVerifyUpdated() {
        await this.saveProduct();
        await this.verifyProductUpdated();
    }

    async verifyProductUpdated() {
        await expect(this.page.locator("#app")).toContainText(
            /Product updated successfully/i,
        );
    }

    async verifyProductCreated() {
        await expect(this.page.locator("#app")).toContainText(
            /Product created successfully/i,
        );
    }

    async updateProductGroupPriceAfterDelete() {
        await this.visit("admin/catalog/products");
        await this.page.getByRole("button", { name: "Create Product" }).click();
        await this.page.locator('select[name="type"]').selectOption("simple");
        await this.page
            .locator('select[name="attribute_family_id"]')
            .selectOption("1");
        await this.page.locator('input[name="sku"]').fill(`sku-${Date.now()}`);
        await this.page.getByRole("button", { name: "Save Product" }).click();
        await this.waitForForm();

        await this.page.getByText("Add New").click();
        await this.page
            .locator('select[name="customer_group_id"]')
            .selectOption("1");
        await this.page.locator('input[name="qty"]').fill("022");
        await this.page.locator('input[name="value"]').fill("045");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            this.page.getByText("For 022 Qty at fixed price of"),
        ).toBeVisible();
        await this.page.waitForTimeout(1000);

        await this.page.getByText("Add New").click();
        await this.page
            .locator('select[name="customer_group_id"]')
            .selectOption("2");
        await this.page.locator('input[name="qty"]').fill("020");
        await this.page
            .locator('select[name="value_type"]')
            .selectOption("discount");
        await this.page.locator('input[name="value"]').fill("034");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            this.page.getByText("For 020 Qty at discount of"),
        ).toBeVisible();
        await this.page.waitForTimeout(1000);

        await this.page.getByText("Add New").click();
        await this.page
            .locator('select[name="customer_group_id"]')
            .selectOption("3");
        await this.page.locator('input[name="qty"]').fill("015");
        await this.page.locator('input[name="value"]').fill("043");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(
            this.page.getByText("For 015 Qty at fixed price of"),
        ).toBeVisible();

        await this.page.getByText("Edit").nth(2).click();
        await this.page.getByRole("button", { name: "Delete" }).click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();

        await expect(
            this.page.getByText("For 022 Qty at fixed price of"),
        ).toBeVisible();
        await expect(
            this.page.getByText("For 020 Qty at discount of"),
        ).not.toBeVisible();
        await expect(
            this.page.getByText("For 015 Qty at fixed price of"),
        ).toBeVisible();
    }

    async editSimpleProduct() {
        await this.openProductForEdit();
        await this.saveAndVerifyUpdated();
    }

    async editConfigurableProduct() {
        await this.openProductForEdit();
        await this.saveAndVerifyUpdated();
    }

    async editGroupedProduct() {
        await this.openProductForEdit();
        await this.saveAndVerifyUpdated();
    }

    async editVirtualProduct() {
        await this.openProductForEdit();
        await this.priceInput.fill("100");
        await this.inventoryInput.fill("1000");
        await this.page
            .locator("#description_ifr")
            .contentFrame()
            .locator("html")
            .click();
        await this.saveAndVerifyUpdated();
    }

    async editDownloadableProduct() {
        await this.openProductForEdit();
        await this.priceInput.fill("100");
        await this.page.getByText("Edit", { exact: true }).first().click();
        await this.page.waitForSelector(".min-h-0 > div > div");
        await this.page
            .locator('input[name="file"]')
            .nth(1)
            .setInputFiles(
                path.resolve(__dirname, "../../../../data/images/2.webp"),
            );
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.page.waitForLoadState("networkidle");
        await this.saveAndVerifyUpdated();
    }

    async massUpdateProducts(status: "Active" | "Disable" = "Active") {
        const productListPage = new ProductListPage(this.page);

        await productListPage.massUpdateStatus(status);
    }
}
