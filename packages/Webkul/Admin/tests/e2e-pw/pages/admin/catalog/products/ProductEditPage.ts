import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import type { AdminPage } from "../../../../setup";
import { ProductListPage } from "./ProductListPage";

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

    private get specialPriceInput() {
        return this.page.locator('input[name="special_price"]');
    }

    private get weightInput() {
        return this.page.locator("#weight");
    }

    private get inventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    private get allowRmaInput() {
        return this.page.locator('input[type="checkbox"][name="allow_rma"]');
    }

    private get allowRmaToggle() {
        return this.page.locator('label[for="allow_rma"]');
    }

    private get updatedMessage() {
        return this.page.getByText("Product updated successfully");
    }

    private get statusInput() {
        return this.page.locator('input[type="checkbox"][name="status"]');
    }

    private get statusToggle() {
        return this.page.locator('label[for="status"]');
    }

    private get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    private get taxCategoryField() {
        return this.page.locator(
            'div.relative:has(> div[name="tax_category_id"])',
        );
    }

    private get taxCategoryTrigger() {
        return this.taxCategoryField.locator('> div[name="tax_category_id"]');
    }

    private get taxCategoryInput() {
        return this.taxCategoryField.locator(
            'input[type="hidden"][name="tax_category_id"]',
        );
    }

    private taxCategoryOption(label: string) {
        return this.taxCategoryField
            .locator("div.max-h-60 > div")
            .filter({ hasText: new RegExp(`^\\s*${label}\\s*$`) });
    }

    async waitForForm() {
        await this.waitForVueMount();

        await expect(this.saveButton).toBeVisible();
        await expect(this.skuInput).toHaveValue(/.+/);
    }

    async openProduct(name: string) {
        await new ProductListPage(this.page).openProduct(name);
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
            await (this.page as AdminPage).fillInTinymce(
                "#short_description_ifr",
                shortDescription,
            );
        }

        if (description) {
            await (this.page as AdminPage).fillInTinymce(
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

    async fillPrice(price: number | string) {
        await this.priceInput.fill(String(price));
    }

    async fillWeight(weight: number | string) {
        await this.weightInput.fill(String(weight));
    }

    async fillInventory(quantity: number | string) {
        await this.inventoryInput.click();
        await this.inventoryInput.fill(String(quantity));
    }

    async setAllowRma(enabled: boolean) {
        if ((await this.allowRmaInput.isChecked()) !== enabled) {
            await this.allowRmaToggle.click();
        }

        await expect(this.allowRmaInput).toBeChecked({ checked: enabled });
    }

    async setTaxCategory(label: string) {
        await this.taxCategoryTrigger.click();
        await this.taxCategoryOption(label).click();

        await expect(this.taxCategoryInput).toHaveValue(/\d+/);
        await expect(this.taxCategoryTrigger).toContainText(label);
    }

    async assignTaxCategory(productName: string, label: string) {
        await this.openProduct(productName);
        await this.setTaxCategory(label);
        await this.saveProduct();
        await this.expectProductUpdated();
    }

    async saveProduct() {
        await this.saveButton.click();
    }

    async saveAndVerifyUpdated(redirectToList: boolean = true) {
        await this.saveProduct();
        await this.expectProductUpdated();

        if (redirectToList) {
            await this.visit("admin/catalog/products");
        }
    }

    async setSpecialPrice(name: string, price: string) {
        await this.openProduct(name);
        await this.specialPriceInput.fill(price);
        await this.saveProduct();
        await this.expectProductUpdated();
    }

    async updatePrice(name: string, price: string): Promise<void> {
        await this.openProduct(name);
        await this.fillPrice(price);
        await this.saveProduct();
        await this.expectProductUpdated();
    }

    async setStatus(name: string, enabled: boolean): Promise<void> {
        await this.openProduct(name);

        if ((await this.statusInput.isChecked()) !== enabled) {
            await this.statusToggle.click();
        }

        await expect(this.statusInput).toBeChecked({ checked: enabled });

        await this.saveProduct();
        await this.expectProductUpdated();
    }

    async submitWithoutRequiredFields(name: string): Promise<void> {
        await this.openProduct(name);
        await this.nameInput.fill("");
        await this.priceInput.fill("");
        await this.saveProduct();
    }

    async expectProductUpdated() {
        await expect(this.updatedMessage).toBeVisible();
    }

    async expectProductCreated() {
        await expect(
            this.page.getByText("Product created successfully"),
        ).toBeVisible();
    }

    async expectTaxCategoryAssigned(productName: string, label: string) {
        await this.openProduct(productName);

        await expect(this.taxCategoryTrigger).toContainText(label);
    }

    async expectPriceInEditForm(name: string, price: string): Promise<void> {
        await this.openProduct(name);

        await expect(this.priceInput).toHaveValue(new RegExp(`^${price}(\\.0+)?$`));
    }

    async expectValidationError(message: string): Promise<void> {
        await expect(
            this.validationErrors.filter({ hasText: message }).first(),
        ).toBeVisible();
    }

    async expectStillOnEditForm(): Promise<void> {
        await expect(this.page).toHaveURL(/catalog\/products\/edit\/\d+/);
    }
}
