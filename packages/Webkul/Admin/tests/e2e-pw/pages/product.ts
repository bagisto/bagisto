import fs from "fs";
import { expect, Page } from "@playwright/test";
import { ProductEditPage } from "../locators/admin/product";
import { CommonPage } from "../utils/tinymce";
import { BaseProduct } from "./types/product.types";

export class ProductCreation {
    constructor(
        private page: Page,

        private productEditPage = new ProductEditPage(page),

        private editor = new CommonPage(page),
    ) {}

    async gotoProductPage() {
        await this.page.goto("admin/catalog/products");
    }

    /**
     * CORE FLOW
     */
    async createProduct(product: BaseProduct) {
        await this.gotoProductPage();
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    async createProductWithoutRMARule(product: BaseProduct) {
        await this.gotoProductPage();
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    /**
     * COMMON STEPS
     */
    private async openCreateModal(type: string, sku: string) {
        await this.productEditPage.createProductButton.click();
        await this.productEditPage.selectProductType.selectOption(type);
        await this.productEditPage.selectAttribute.selectOption("1");
        await this.productEditPage.productSku.fill(sku);
        await this.productEditPage.saveProduct.click();
        //         await expect(this.page.locator("#app")).toContainText(
        //     /product created successfully/i
        // );
        await expect(this.page).toHaveURL(
            /\/admin\/catalog\/products\/edit\/\d+/,
        );
        await this.page.waitForLoadState("networkidle");
    }

    private async fillCommonDetails(product: BaseProduct) {
        await this.page.waitForTimeout(1000);
        await this.productEditPage.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.productEditPage.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.productEditPage.productDescription,
            product.description,
        );
    }

    /**
     * PRODUCT TYPE HANDLERS
     */
    private async handleProductType(product: BaseProduct) {
        switch (product.type) {
            case "simple":
                await this.simple(product);
                break;

            default:
                throw new Error(`Unsupported product type: ${product.type}`);
        }
    }

    private async simple(product: BaseProduct) {
        if (product.price)
            await this.productEditPage.productPrice.fill(
                product.price.toString(),
            );

        if (product.weight)
            await this.productEditPage.productWeight.fill(
                product.weight.toString(),
            );

        if (product.inventory)
            await this.productEditPage.productInventory
                .first()
                .fill(product.inventory.toString());
        await this.page.locator('label[for="allow_rma"]').click();
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.productEditPage.saveProduct.click();
        await expect(
            this.productEditPage.updateProductSuccessToast,
        ).toBeVisible();
    }

    /**
     * Save Product Data in JSON File
     */
    private saveProductToJson(product: BaseProduct) {
        const filePath = "product-data.json";

        const productData = {
            name: product.name,
            sku: product.sku,
            type: product.type,
        };

        // Write SINGLE product
        fs.writeFileSync(filePath, JSON.stringify(productData, null, 2));
    }
}
