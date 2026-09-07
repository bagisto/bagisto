import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { generateSKU } from "../../../../utils/faker";
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

    async openProductList() {
        await this.visit("admin/catalog/products");
        await expect(this.createButton).toBeVisible();
    }

    async openCreateModal() {
        await this.openProductList();
        await this.createButton.click();
        await expect(this.typeSelect).toBeVisible();
    }

    async fillType(type: string) {
        await this.typeSelect.selectOption(type);
    }

    async fillAttributeFamily(attributeFamily: string | { label: string }) {
        await this.attributeFamilySelect.selectOption(attributeFamily);
    }

    async fillSku(sku: string) {
        await this.skuInput.fill(sku);
    }

    async submit() {
        await this.saveProductButton.click();
    }

    async createProduct(
        type: string,
        attributeFamily: string | { label: string },
        sku: string,
    ) {
        await this.openCreateModal();
        await this.fillType(type);
        await this.fillAttributeFamily(attributeFamily);
        await this.fillSku(sku);
        await this.submit();
    }

    async createSimpleProduct(product: BaseProduct) {
        await this.createProduct("simple", "1", generateSKU());

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
    }
}
