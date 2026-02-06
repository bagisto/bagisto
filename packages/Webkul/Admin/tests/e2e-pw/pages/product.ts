import fs from "fs";
import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
import { CommonPage } from "../utils/tinymce";
import { BaseProduct } from "./types/product.types";

export class ProductCreation {
    constructor(
        private page: Page,

        private locators = new WebLocators(page),

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
        await this.locators.createProductButton.click();
        await this.locators.selectProductType.selectOption(type);
        await this.locators.selectAttribute.selectOption("1");
        await this.locators.productSku.fill(sku);
        await this.locators.saveProduct.click();
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
        await this.locators.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.locators.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.locators.productDescription,
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
            await this.locators.productPrice.fill(product.price.toString());

        if (product.weight)
            await this.locators.productWeight.fill(product.weight.toString());

        if (product.inventory)
            await this.locators.productInventory
                .first()
                .fill(product.inventory.toString());
        await this.page.locator('label[for="allow_rma"]').click();
        await this.locators.rmaSelection.selectOption("1");
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.locators.saveProduct.click();
        await expect(this.locators.updateProductSuccessToast).toBeVisible();
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
