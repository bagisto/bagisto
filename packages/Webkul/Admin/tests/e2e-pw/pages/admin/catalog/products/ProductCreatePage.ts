import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { uniqueStamp } from "../../../../utils/faker";
import { ProductEditPage } from "./ProductEditPage";
import { BaseProduct } from "../../types/product.types";

export class ProductCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get attributeFamilySelect() {
        return this.page.locator('select[name="attribute_family_id"]');
    }

    private get skuInput() {
        return this.page.locator('input[name="sku"]');
    }

    private get saveProductButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    private async openCreateModal(): Promise<void> {
        await this.visit("admin/catalog/products");

        await expect(this.createButton).toBeVisible();

        await this.createButton.click();

        await expect(this.typeSelect).toBeVisible();
    }

    private async submitCreateModal(
        type: string,
        attributeFamily: string | { label: string },
        sku: string,
    ): Promise<void> {
        await this.openCreateModal();
        await this.typeSelect.selectOption(type);
        await this.attributeFamilySelect.selectOption(attributeFamily);
        await this.skuInput.fill(sku);
        await this.saveProductButton.click();
    }

    async attemptCreateProduct(sku: string, type: string = "simple"): Promise<void> {
        await this.submitCreateModal(type, "1", sku);
    }

    async createSimpleProduct(
        product: BaseProduct,
        attributeFamily: string | { label: string } = "1",
    ): Promise<string> {
        const sku = product.sku ?? `SKU-${uniqueStamp()}`;

        await this.submitCreateModal("simple", attributeFamily, sku);

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();

        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });

        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );

        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });

        await productEditPage.fillPrice(product.price ?? "");
        await productEditPage.fillWeight(product.weight ?? "");
        await productEditPage.fillInventory(product.inventory ?? "");

        if (product.allowRma) {
            await productEditPage.setAllowRma(true);
        }

        await productEditPage.saveAndVerifyUpdated();

        return sku;
    }

    async expectCreateRefused(message: string): Promise<void> {
        await expect(
            this.validationErrors.filter({ hasText: message }).first(),
        ).toBeVisible();
        await expect(this.typeSelect).toBeVisible();
    }
}
